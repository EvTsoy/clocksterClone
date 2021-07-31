<?php

namespace App\Conversation\Flows;

class DateOfBirth extends AbstractFlow
{
    public function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Напишите дату своего рождения (пример: 31.03.1988). Минимальный возвраст - 18 лет.',
        ]);

        app()->call('App\Http\Controllers\UserStateController@updateState', [
            'id' => $this->user->id,
            'status' => 'dateOfBirth'
        ]);
    }

    public function storeData()
    {
        app()->call('App\Http\Controllers\UserController@updateDateOfBirth', [
            'id' => $this->user->id,
            'dateOfBirth' => $this->message->message_text
        ]);

        app()->call('App\Http\Controllers\UserStateController@updateState', [
            'id' => $this->user->id,
            'status' => 'profile'
        ]);
    }
}
