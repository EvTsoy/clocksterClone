<?php


namespace App\Conversation\Flows;



use Telegram\Bot\Keyboard\Keyboard;

class Contacts extends AbstractFlow
{
    protected $states = [
        'contacts'
    ];

    public function first()
    {
        $state = app()->call('App\Http\Controllers\UserStateController@show', [
            'id' => $this->user->id
        ]);

        if(hash_equals($state->status, 'contacts')) {
            $this->contacts();
        }
    }

    public function contacts()
    {

        $btn = Keyboard::button([
            'text' => 'Share my phone number',
            'request_contact' => true
        ]);

        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Telephone',
            'reply_markup' => Keyboard::make([
                'keyboard' => [[$btn]],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ]);
    }


}
