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

    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }


    public function start(User $user, Message $message)
    {
        Log::debug('Conversation.start', [
            'user' => $user->toArray(),
            'message' => $message->toArray(),
        ]);

        foreach ($this->flows as $flow) {
            $flow = app($flow);
            $flow->setUser($user);
            $flow->setMessage($message);

            $state = $flow->run();

            if(is_string($state)) {
                $this->context->save($user, $flow, $state);
                break;
            }
        }
    }
}
