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
    ];

    public function start(User $user, Message $message, string $state)
    {
        Log::debug('Conversation.start', [
            'user' => $user->toArray(),
            'message' => $message->toArray(),
        ]);

        foreach ($this->flows as $flow)
        {
            $flow = app($flow);
            $flow->setUser($user);
            $flow->setMessage($message);
            $flow->setState($state);
            $flow->run();
        }
    }
}
