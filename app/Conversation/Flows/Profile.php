<?php

namespace App\Conversation\Flows;

use Telegram\Bot\Keyboard\Keyboard;

class Profile extends AbstractFlow
{
    public function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' =>
                'Ваш профиль готов!'
                . "\nМы уже начали подбирать для вас подходящие вакансии. "
                . "Также вы сами можете начать поиск, используя команду /search"
            ,
            'reply_markup' => Keyboard::make([
                'keyboard' => [
                    [
                        [
                            'text' => 'Профиль',
                            'callback_data' => 'profile.data'
                        ],
                    ],
                    [
                        [
                            'text' => 'Проставить приход',
                            'callback_data' => 'checkin.data'
                        ],
                    ],
                    [
                        [
                            'text' => 'Мои приходы',
                            'callback_data' => 'allCheckin.data'
                        ],
                    ]
                ],
            ])
        ]);
    }

    public function showProfile()
    {
        $this->telegram()->sendMessage([
            'parse_mode' => 'HTML',
            'chat_id' => $this->user->user_telegram_id,
            'text' =>
                "Как видят ваш профиль работодатели:"
                . "\n\n<b>" . $this->user->first_name . "</b>"
                . "\n<b>Ваш телефон: </b>" . $this->user->phone_number
                . "\n<b>Год рождения: </b>" . $this->user->date_of_birth
                . "\n<b>Город поиска: </b>" . $this->user->city
                . "\n\n---"
                . "\n Вы можете изменить параметры своего профиля, нажав на соответствующую кнопку ниже",
            'reply_markup' => Keyboard::make([
                'inline_keyboard' =>
                    array(
                        array(
                            array(
                                'text' => 'Имя',
                                'callback_data' => 'editName'
                            ),
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
