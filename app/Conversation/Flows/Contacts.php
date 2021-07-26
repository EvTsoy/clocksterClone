<?php


namespace App\Conversation\Flows;



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
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Telephone',
        ]);
    }


}
