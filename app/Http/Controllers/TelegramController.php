<?php

namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function process()
    {
        $update = Telegram::bot()->getWebhookUpdate();
        $message = $update->getMessage();

        $user = $message->from;

        //Сохраненяем пользователя
        $user = app()->call('App\Http\Controllers\UserController@store', [
                'user_telegram_id' => $user->id,
                'first_name' => $user->firstName,
                'last_name' => $user->lastName,
                'username' => $user->username
            ]
        );

        //Сохранение сообщений
        app()->call('App\Http\Controllers\MessageController@store', [
            'user_telegram_id' => $user->id,
            'message_id' => $message->messageId,
            'message_text' => $message->text
        ]);

        if(hash_equals($message->text, '/start'))
        {
            Telegram::bot()->sendMessage([
                'chat_id' => $user->user_telegram_id,
                'text' => 'Для продолжения работы с ботом вам необходимо ознакомиться и принять условия "Политики конфиденциальности". Если вы согласны с условиями, то нажмите кнопку "Принять"'
            ]);
        }
    }
}
