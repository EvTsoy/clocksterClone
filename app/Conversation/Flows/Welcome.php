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
        "inline_keyboard" => array(array(array("text" => "My Button Text", "callback_data" => "myCallbackData")))

        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Это Клон. Для продолжения работы с ботом вам необходимо ознакомиться и принять условия "Политики конфиденциальности". Если вы согласны с условиями, то нажмите кнопку "Принять"',
            'reply_markup' => Keyboard::make([
                'inline_keyboard' => [
                    [
                        [
                        'text' => 'Политика конфидециальности',
                        'url' => 'https://clockster.com/ru/confidentiality/'
                        ],
                        [
                        'text' => 'Принять'
                        ]
                    ]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ]);
    }
}
