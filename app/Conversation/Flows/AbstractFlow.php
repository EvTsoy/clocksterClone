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

    protected $state;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    protected function telegram(): Api
    {
        return Telegram::bot();
    }

}
