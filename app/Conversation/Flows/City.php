<?php

namespace App\Conversation\Flows;

use Telegram\Bot\Keyboard\Keyboard;

class City extends AbstractFlow
{
    public function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Выберите город, в котором вы желаете найти работу',
            'reply_markup' => Keyboard::make([
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Almaty',
                            'callback_data' => 'city.Almaty'
                        ],
[
                            'text' => 'Astana',
                            'callback_data' => 'city.Astana'
                        ],
[
                            'text' => 'Moscow',
                            'callback_data' => 'city.Moscow'
                        ],
                        [
                            'text' => 'Tashkent',
                            'callback_data' => 'city.Tashkent'
                        ]
                    ]
                ],
            ])
        ]);
    }

    public function saveCity($city)
    {
        app()->call('App\Http\Controllers\UserController@updateCity', [
            'id' => $this->user->id,
            'city' => $city
        ]);

        app()->call('App\Http\Controllers\UserStateController@updateState', [
            'id' => $this->user->id,
            'status' => 'profile'
        ]);
    }
}
