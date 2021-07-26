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

        foreach ($this->flows as $flow) {
            $flow = app($flow);
            $flow->setUser($user);
            $flow->setMessage($message);
            $flow->setContext($context);
            if (hash_equals($option, '')) {
                $flow->run();
            } else if (hash_equals($option, 'accepted')) {
                $flow->accepted();
            }
        }
    }
}
