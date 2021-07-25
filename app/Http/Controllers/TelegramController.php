<?php

namespace App\Http\Controllers;

use Log;
use App\Conversation\Conversation;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function process()
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

        $state =  $update->isType('callback_query') ? $update->callbackQuery->data : 'welcome';

        //Начало диалога
        $conversation = new Conversation();

        if (hash_equals($state, 'welcome'))
        {
            $conversation->start($user, $message);
        }

        if (hash_equals($state, 'accepted'))
        {
            $conversation->intro($user);
        }
    }
}
