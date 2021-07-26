<?php

namespace App\Conversation;

use App\Conversation\Flows\Fullname;
use App\Conversation\Flows\Welcome;
use App\Models\Message;
use App\Models\User;
use Log;

class Conversation
{
    protected $flows = [
        Welcome::class,
        Fullname::class
    ];

    public function start(User $user, Message $message, $option)
    {
        Log::debug('Conversation.start', [
            'user' => $user->toArray(),
            'message' => $message->toArray(),
        ]);

        $context = Context::get($user);

        $state = app()->call('App\Http\Controllers\UserStateController@show', [
            'id' => $user->id
        ]);

        foreach ($this->flows as $flow) {
            $flow = app($flow);
            $this->setData($flow, $user, $message, $context);
            $flow->run();
        }

        if(hash_equals($option, 'accepted')) {
            $flow = app(Welcome::class);
            $this->setData($flow, $user, $message, $context);
            $flow->accepted();
        }

        if(hash_equals($state->state, 'intro')) {
            $flow = app(Fullname::class);
            $this->setData($flow, $user, $message, $context);
            $flow->storeUserName();
        }

    }

    private function setData($flow, User $user, Message $message, $context)
    {
        $flow->setUser($user);
        $flow->setMessage($message);
        $flow->setContext($context);
        return $flow;
    }
}
