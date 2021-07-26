<?php

namespace App\Conversation;

use App\Conversation\Flows\Contacts;
use App\Conversation\Flows\Fullname;
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
            'state.state' => $state,
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

        if(hash_equals($state->status, 'city'))
        {
            $flow = app(City::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }
    }

    private function setData($flow, User $user, Message $message)
    {
        $flow->setUser($user);
        $flow->setMessage($message);
        return $flow;
    }
}
