<?php

namespace App\Conversation\Flows;

use Log;

class Fullname extends AbstractFlow
{
    protected $triggers = [
        'name'
    ];

    public function first()
    {
        Log::debug('Fullname.first', [
            'state' => $this->state,
        ]);

        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Напишите свое имя и фамилию (например: Айгерим Оспанова)'
        ]);
    }
}
