<?php

namespace App\Conversation\Flows;

class Notification extends AbstractFlow
{
    public function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Профиль успешно обновлен',
        ]);
    }

    public function checkedIn()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Приход проставлен',
        ]);
    }
}
