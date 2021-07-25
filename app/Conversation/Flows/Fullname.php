<?php

namespace App\Conversation\Flows;

class Fullname extends AbstractFlow
{
    protected $state = 'accepted';

    public function intro()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Напишите свое имя и фамилию (например: Айгерим Оспанова)'
        ]);
    }
}
