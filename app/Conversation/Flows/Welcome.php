<?php

namespace App\Conversation\Flows;

use Telegram\Bot\Keyboard\Keyboard;

use Log;

class Welcome extends AbstractFlow
{
    public function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Это Клон. Для продолжения работы с ботом вам необходимо ознакомиться и принять условия "Политики конфиденциальности". Если вы согласны с условиями, то нажмите кнопку "Принять"',
            'reply_markup' => Keyboard::make([
                'inline_keyboard' =>
                    array(

                        array(
                            array(
                                'text' => 'Политика конфидециальности',
                                'url' => 'https://clockster.com/ru/confidentiality/'
                            ),
                        ),

                        array(
                            array(
                                'text' => 'Принять',
                                'callback_data' => 'accepted'
                            )
                        )
                    ),
                ])
        ]);
    }
}
