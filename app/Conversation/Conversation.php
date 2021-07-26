<?php

namespace App\Conversation;

use App\Conversation\Flows\Fullname;
use App\Conversation\Flows\Welcome;
use App\Models\Message;
use App\Models\User;
use Log;

class Conversation
{
    protected $flows = [
        Welcome::class,
        Fullname::class,
    ];

    public function start($update)
    {
        $message = $update->getMessage();
        $user = $message->from;

        Log::debug('Conversation.start', [
            'user' => $user->toArray(),
            'message' => $message->toArray(),
        ]);

        //Сохраненяем пользователя
        $user = app()->call('App\Http\Controllers\UserController@store', [
            'user' => $user
        ]);

        //Сохранение сообщений
        $message = app()->call('App\Http\Controllers\MessageController@store', [
            'message' => $message
        ]);

        foreach ($this->flows as $flow) {
            $flow = app($flow);
            $flow->setUser($user);
            $flow->setMessage($message);
        }

        if (hash_equals($message->message_text, '/start')) {
            $flow = app(Welcome::class);
            $flow->first();
        }

        if ($update->isType('callback_query')) {
            if (hash_equals($update->callbackQuery->data, 'accepted')) {
                $flow = app(Fullname::class);
                $flow->intro();
            }
        }
    }
}
