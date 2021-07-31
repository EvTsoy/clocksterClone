<?php

namespace App\Conversation;

use App\Conversation\Flows\AbstractConversation;
use App\Conversation\Flows\City;
use App\Conversation\Flows\Contacts;
use App\Conversation\Flows\DateOfBirth;
use App\Conversation\Flows\Fullname;
use App\Conversation\Flows\Profile;
use App\Conversation\Flows\Welcome;
use App\Models\Message;
use App\Models\User;

class Conversation extends AbstractConversation

{
    public function start(User $user, Message $message, $option)
    {
        $state = $this->getStatus($user);
        
        // Политика конфидециальности
        // Если пишет сообщения то бот отправляет политику еще раз
        if(hash_equals($state->status, 'first') ||
            hash_equals($message->message_text, '/start')) {

            $flow = app(Welcome::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }

        // Политика конфидециальности принятие
        // Здесь состояние пользователя поменяется на accepted
        if(hash_equals($option, 'accepted')) {
            $flow = app(Fullname::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }

        if(hash_equals($option, 'editName')) {
            $flow = app(Fullname::class);
            $this->setData($flow, $user, $message);
            $flow->first();

            app()->call('App\Http\Controllers\UserStateController@updateState', [
                'id' => $user->id,
                'status' => 'editName'
            ]);

        }
        if(hash_equals($state->status, 'editName')) {
            //Сохраняем имя
            $flow = app(Fullname::class);
            $this->setData($flow, $user, $message);
            $flow->storeUserName();
        }

        // Сообщение которое написано не имеет option но состояние пользователя intro
        if(hash_equals($state->status, 'intro')) {
            //Сохраняем имя
            $flow = app(Fullname::class);
            $this->setData($flow, $user, $message);
            $flow->storeUserName();

            $flow = app(Contacts::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }

        if(hash_equals($state->status, 'phone')) {
            $flow = app(Contacts::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }

        if(hash_equals($option, 'contacts')) {
            $flow = app(Contacts::class);
            $this->setData($flow, $user, $message);
            $flow->storePhone();

            $flow = app(City::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }

        if(hash_equals($state->status, 'city')) {
            $flow = app(City::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }

        if(str_contains($option, 'city.')) {
            $flow = app(City::class);
            $this->setData($flow, $user, $message);
            $city = str_replace('city.', '', $option);
            $flow->saveCity($city);

            $flow = app(DateOfBirth::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }

        if(hash_equals($state->status, 'dateOfBirth'))
        {
            $flow = app(DateOfBirth::class);
            $this->setData($flow, $user, $message);

            $flow->storeDateOfBirth();
            $flow = app(Profile::class);
            $this->setData($flow, $user, $message);
            $flow->first();

        }

        if(hash_equals($option, 'profile.data')) {
            $flow = app(Profile::class);
            $this->setData($flow, $user, $message);
            $flow->showData();
        }
    }

    private function setData($flow, User $user, Message $message)
    {
        $flow->setUser($user);
        $flow->setMessage($message);
        return $flow;
    }
}
