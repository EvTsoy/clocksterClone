<?php

namespace App\Conversation\Flows;

use App\Models\Message;
use App\Models\State;
use App\Models\User;

abstract class AbstractConversation
{

    protected $user;

    protected $message;

    protected $city;

    public function getStatus(User $user)
    {
        $state = app()->call('App\Http\Controllers\UserStateController@show', [
            'id' => $user->id
        ]);

        if(is_null($state)) {
            $state = app()->call('App\Http\Controllers\UserStateController@store', [
                'values' => [
                    'user_id' => $user->id,
                    'status' => 'first'
                ]
            ]);
        }
        return $state;
    }

    public function sendMessage($flowClass)
    {
        $flow = app($flowClass);
        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        $flow->first();
    }

    public function storeData($flowClass)
    {
        $flow = app($flowClass);
        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        $flow->storeData();
    }

    public function storeCity($flowClass)
    {
        $flow = app($flowClass);
        $flow->setUser($this->user);
        $flow->setCity($this->city);
        $flow->setMessage($this->message);
        $flow->storeData();
    }

    public function showProfile($flowClass)
    {
        $flow = app($flowClass);
        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        $flow->showProfile();
    }
}
