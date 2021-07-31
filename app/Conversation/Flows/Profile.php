<?php

namespace App\Conversation\Flows;

use Telegram\Bot\Keyboard\Keyboard;

class Profile extends AbstractFlow
{
    public function first()
    {
        app()->call('App\Http\Controllers\UserStateController@updateState', [
            'id' => $this->user->id,
            'status' => 'ready'
        ]);

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

    public function showProfile()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' =>
                "Ваше имя: "
                . $this->user->first_name
                . "\nВаш телефон: " . $this->user->phone_number
                . "\nГод рождения: " . $this->user->date_of_birth
                . "\nГород поиска: " . $this->user->city,
            'reply_markup' => Keyboard::make([
                'inline_keyboard' =>
                    array(
                        array(
                            array(
                                'text' => 'Имя',
                                'callback_data' => 'editName'
                            ),
                        ),
                        array(
                            array(
                                'text' => 'Дата рождения',
                                'callback_data' => 'editYear'
                            ),
                        ),
                        array(
                            array(
                                'text' => 'Город',
                                'callback_data' => 'editCity'
                            )
                        )
                    ),
            ])
        ]);
    }
}
