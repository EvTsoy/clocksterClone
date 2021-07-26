<?php


namespace App\Conversation\Flows;

class Contacts extends AbstractFlow
{
    public function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Telephone',
        ]);
    }
}
