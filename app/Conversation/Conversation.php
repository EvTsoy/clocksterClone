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
                    'state' => 'first'
                ]
            ]);
        }

        Log::debug('Conversation.start', [
            'state.status' => $state,
        ]);

        if(hash_equals($state->status, 'first')) {
            $flow = app(Welcome::class);
            $this->setData($flow, $user, $message);
            $flow->first();
        }

        if(!is_null($state->status) && hash_equals($option, 'accepted')) {
            $flow = app(Welcome::class);
            $this->setData($flow, $user, $message);
            $flow->accepted();
        }

        if(!is_null($state->status) && hash_equals($state->status, 'intro')) {
            $flow = app(Fullname::class);
            $this->setData($flow, $user, $message);
            $flow->storeUserName();
        }

    }

    private function setData($flow, User $user, Message $message)
    {
        $flow->setUser($user);
        $flow->setMessage($message);
        return $flow;
    }
}
