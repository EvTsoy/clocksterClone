<?php

namespace App\Conversation\Flows;

use Log;
use Telegram;
use App\Models\User;
use Telegram\Bot\Api;
use App\Models\Message;

abstract class AbstractFlow
{
    protected $user;

    protected $message;

    protected $triggers = [];

    protected $states = [];

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    protected function telegram(): Api
    {
        return Telegram::bot();
    }

    public function run($state = null)
    {
        Log::debug(static::class . '.run', [
            'user' => $this->user->toArray(),
            'message' => $this->message->toArray(),
        ]);

        foreach ($this->triggers as $trigger)
        {
            if(hash_equals($trigger, $this->message->message_text))
            {
                $this->first();
            }
        }
    }

    abstract protected function first();
}
