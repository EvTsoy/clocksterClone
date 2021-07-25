<?php

namespace App\Conversation\Flows;

use Telegram\Bot\Keyboard\Keyboard;

class Welcome extends AbstractFlow
{
    protected $triggers = [
        '/start'
    ];

    public function first()
    {
        $buttons = ['Политика конфидециальности', 'Принять'];

        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Это Клон. Для продолжения работы с ботом вам необходимо ознакомиться и принять условия "Политики конфиденциальности". Если вы согласны с условиями, то нажмите кнопку "Принять"',
            'reply_markup' => Keyboard::make([
                'inline_keyboard' => $buttons,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ]);
    }
}
