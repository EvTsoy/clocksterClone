<?php

namespace App\Conversation\Flows;

use Telegram;
use App\Models\User;
use Telegram\Bot\Api;
use App\Models\Message;

abstract class AbstractFlow
{
    protected $user;

    protected $message;

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
}
