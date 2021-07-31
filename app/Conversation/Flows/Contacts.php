<?php


namespace App\Conversation\Flows;



use Telegram\Bot\Keyboard\Keyboard;

class Contacts extends AbstractFlow
{
    public function first()
    {
        $btn = Keyboard::button([
            'text' => 'Отправить номер',
            'request_contact' => true
        ]);

        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Для того, чтобы работодатель смог с вами связаться, нам нужен ваш номер телефона. Нажмите на кнопку "Отправить номер", открыв меню через иконку справа от строки ввода текста сообщения',
            'reply_markup' => Keyboard::make([
                'keyboard' => [[$btn]],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ]);
    }
}
