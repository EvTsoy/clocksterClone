<?php

namespace App\Conversation\Flows;

use Telegram\Bot\Keyboard\Keyboard;

class City extends AbstractFlow
{
    public function first()
    {
        $cities = array();
        $citiesCollection = app()->call('App\Http\Controllers\CityController@index')->toArray();

        for ($i = 0; $i < count($citiesCollection); $i++)
        {
            $row = array();
            $row['text'] = $citiesCollection[$i]['name'];
            $row['callback_data'] = 'city.' . $citiesCollection[$i]['name'];
            $cities[] = $row;
        }
        
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Выберите город, в котором вы желаете найти работу',
            'reply_markup' => Keyboard::make([
                'inline_keyboard' => array_chunk($cities, 3)
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
