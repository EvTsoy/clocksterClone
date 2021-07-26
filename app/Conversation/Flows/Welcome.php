<?php

namespace App\Conversation\Flows;

use Telegram\Bot\Keyboard\Keyboard;

use Log;

class Welcome extends AbstractFlow
{
    protected $triggers = [
        '/start'
    ];

    protected $states = [
        'first'
    ];

    protected function first()
    {
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
                        'text' => 'Принять',
                        'callback_data' => 'accepted'
                        ]
                    ]
                ],
            ])
        ]);
    }

    public function accepted()
    {

        app()->call('App\Http\Controllers\UserStateController@updateState', [
            'id' => $this->user->id,
            'status' => 'intro'
        ]);
        $this->jump(Fullname::class);
    }
}
