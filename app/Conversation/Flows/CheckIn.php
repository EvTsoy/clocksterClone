<?php

namespace App\Conversation\Flows;

use Carbon\Carbon;
use Telegram\Bot\Keyboard\Keyboard;

class CheckIn extends AbstractFlow
{
    public function first()
    {
        $btn = Keyboard::button([
            'text' => 'Отправить локацию',
            'request_location' => true
        ]);

        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Отправьте локацию',
            'reply_markup' => Keyboard::make([
                'keyboard' => [[$btn]],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ]);
    }

    public function sendAllCheckedIns()
    {
        $checkins = app()
            ->call('App\Http\Controllers\CheckInController@index')
            ->each(function ($checkin) {
                $this->telegram()->sendMessage([
                    'parse_mode' => 'HTML',
                    'chat_id' => $this->user->user_telegram_id,
                    'text' =>
                        "Ваши приходы:"
                        . "\n<b>Время: </b>" . Carbon::createFromTimestamp($checkin->time)->toDateTimeString()
                        . "\n<b>Широта: </b>" . $checkin->lat
                        . "\n<b>Долгота: </b>" . $checkin->lng
                        . "\n\n---"
                ]);
            });
    }
}
