<?php

namespace App\Conversation;

use App\Conversation\Flows\City;
use App\Conversation\Flows\Contacts;
use App\Conversation\Flows\DateOfBirth;
use App\Conversation\Flows\Fullname;
use App\Conversation\Flows\Profile;
use App\Conversation\Flows\Welcome;
use App\Models\Message;
use App\Models\User;
use Log;

class Conversation
{
    public function start(User $user, Message $message, $option)
    {
        $state = app()->call('App\Http\Controllers\UserStateController@show', [
            'id' => $user->id
        ]);

        if(is_null($state)) {
            $state = app()->call('App\Http\Controllers\UserStateController@store', [
                'values' => [
                    'user_id' => $user->id,
                    'status' => 'first'
                ]
            ]);
        }

        Log::debug('Conversation.start', [
            'Conversation.message' => $message,
        ]);

        if(
            hash_equals($state->status, 'first') ||
            hash_equals($message->message_text, '/start')
        ) {
            $flow = app(Welcome::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }

        if(hash_equals($option, 'accepted')) {
            $flow = app(Fullname::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }

        if(hash_equals($state->status, 'intro')) {
            $flow = app(Fullname::class);
            $this->setData($flow, $user, $message);
            $flow->storeUserName();

            $flow = app(Contacts::class);
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

            if(preg_match("/\([0-9]{2}\.[0-9]{2}\.([0-9]{4})\)/", $message->message_text))
            {
                $flow->storeDateOfBirth();

                $flow = app(Profile::class);
                $this->setData($flow, $user, $message);
                $flow->first();
            } else {
                $flow->first();
            }

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
