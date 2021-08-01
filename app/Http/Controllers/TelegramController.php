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


        if ($update->isType('callback_query')) {

            $option = $update->callbackQuery->data;

            // Запрос и сохранение сообщения
            $message = $update->getMessage();
            $message = app()->call('App\Http\Controllers\MessageController@store', [
                'message' => $message
            ]);

            //Запрос и сохранение пользователя
            $user = app()->call('App\Http\Controllers\UserController@show', [
                'id' => $update->callbackQuery->from->id
            ]);

            $state = app()->call('App\Http\Controllers\UserStateController@show', [
                'id' => $user->id
            ]);

            if($state->status === 'editedCity')
            {
                app()->call('App\Http\Controllers\UserStateController@updateState', [
                    'id' => $user->id,
                    'status' => 'editedCity'
                ]);
            } else {
                app()->call('App\Http\Controllers\UserStateController@updateState', [
                    'id' => $user->id,
                    'status' => $option
                ]);
            }
        }

        else {
            $message = $update->getMessage();
            $user = $message->from;
            $phoneNumber = $message->contact->phoneNumber ?? '';
            $option = '';

            if($phoneNumber !== '') {
                $option = 'contacts';
            }

            if(!is_null($message->text) && hash_equals($message->text, 'Профиль')) {
                $option = 'profile.data';
            }

            //Сохраненяем пользователя
            $user = app()->call('App\Http\Controllers\UserController@store', [
                'user' => $user,
            ]);

            app()->call('App\Http\Controllers\UserController@updatePhone', [
                'id' => $user->id,
                'phoneNumber' => $phoneNumber,
            ]);

            //Сохранение сообщений
            $message = app()->call('App\Http\Controllers\MessageController@store', [
                'message' => $message
            ]);
        }

        //Начало диалога
        $conversation->start($user, $message, $option);
    }
}
