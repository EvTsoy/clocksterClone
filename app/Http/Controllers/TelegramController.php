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
            'user' => $user
        ]);

        //Сохранение сообщений
        app()->call('App\Http\Controllers\MessageController@store', [
            'message' => $message
        ]);

        if(hash_equals($message->text, '/start'))
        {
            Telegram::bot()->sendMessage([
                'chat_id' => $user->user_telegram_id,
                'text' => 'Это Клон. Для продолжения работы с ботом вам необходимо ознакомиться и принять условия "Политики конфиденциальности". Если вы согласны с условиями, то нажмите кнопку "Принять"'
            ]);
        }
    }
}
