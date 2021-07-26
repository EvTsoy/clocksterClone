<?php

namespace App\Conversation\Flows;

use Telegram\Bot\Keyboard\Keyboard;

class Profile extends AbstractFlow
{
    public function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Ваш профиль готов',
            'reply_markup' => Keyboard::make([
                'keyboard' => [
                    [
                        [
                            'text' => 'Профиль',
                            'callback_data' => 'profile.data'
                        ],
                    ]
                ],
            ])
        ]);
    }

    public function showData()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Ваше имя' . $this->user->first_name,
        ]);
    }
}