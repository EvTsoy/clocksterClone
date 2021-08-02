<?php

namespace App\Conversation\Flows;

use Telegram\Bot\Keyboard\Keyboard;

class CheckIn extends AbstractFlow
{
    public function first()
    {
        $btn = Keyboard::button([
            'text' => 'Отправить локацию',
            'request_location' => true
        ]);

        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Отправьте локацию',
            'reply_markup' => Keyboard::make([
                'keyboard' => [[$btn]],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ]);
    }
}
