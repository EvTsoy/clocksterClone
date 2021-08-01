<?php

namespace App\Conversation\Flows;

use Telegram\Bot\Keyboard\Keyboard;

class City extends AbstractFlow
{
    public function first()
    {
        $cities = app()->call('App\Http\Controllers\CityController@index');
        $chunks = $cities->chunk(3);
        $buttons = $chunks->toArray();
        
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Выберите город, в котором вы желаете найти работу',
            'reply_markup' => Keyboard::make([
                'inline_keyboard' =>
                    array($buttons),
            ])
        ]);
    }

    public function storeData()
    {
        $this->user->city = $this->city;
        app()->call('App\Http\Controllers\UserController@updateCity', [
            'id' => $this->user->id,
            'city' => $this->city
        ]);
    }
}
