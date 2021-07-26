<?php

namespace App\Http\Controllers;

use Log;
use App\Conversation\Conversation;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function process(Conversation $conversation)
    {
        $update = Telegram::bot()->getWebhookUpdate();

        Log::debug('Telegram.process', [
            'update' => $update,
        ]);

        $message = $update->getMessage();
        $user = $message->from;

        //Сохраненяем пользователя
        $user = app()->call('App\Http\Controllers\UserController@store', [
            'user' => $user
        ]);

        //Сохранение сообщений
        $message = app()->call('App\Http\Controllers\MessageController@store', [
            'message' => $message
        ]);


        $option = $update->isType('callback_query') ? $update->callbackQuery->data : null;

        //Начало диалога
        $conversation->start($user, $message, $option);

    }
}
