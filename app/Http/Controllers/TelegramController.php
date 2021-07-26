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




        $conversation = new Conversation();

        if ($update->isType('callback_query')) {
            $user_id = $update->callbackQuery->from->id;
            $user = app()->call('App\Http\Controllers\UserController@show', [
                'id' => $user_id
            ]);

            if(hash_equals($update->callbackQuery->data, 'accepted')) {
                $conversation->intro($user);
            }
        } else {
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

            //Начало диалога
            $conversation->start($user, $message);
        }
    }
}
