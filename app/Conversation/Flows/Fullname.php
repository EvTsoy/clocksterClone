<?php

namespace App\Conversation\Flows;

class Fullname extends AbstractFlow
{
    public function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Напишите свое имя и фамилию (например: Айгерим Оспанова)',
        ]);
    }

    public function storeData()
    {
        $this->user->first_name = $this->message->message_text;
        app()->call('App\Http\Controllers\UserController@update', [
            'id' => $this->user->id,
            'name' => $this->message->message_text
        ]);
    }
}
