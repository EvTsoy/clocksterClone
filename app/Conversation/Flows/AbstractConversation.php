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
        $flow = $this->setData($flowClass);
        $flow->first();
    }

    public function sendCustomMessage($flowClass)
    {
        $flow = $this->setData($flowClass);
        $flow->customCity();
    }

    public function storeData($flowClass)
    {
        $flow = $this->setData($flowClass);
        $flow->storeData();
    }

    public function storeCity($flowClass)
    {
        $flow = $this->setData($flowClass);
        $flow->setCity($this->city);
        $flow->storeData();
    }

    public function showProfile($flowClass)
    {
        $flow = $this->setData($flowClass);
        $flow->showProfile();
    }

    public function setData($flowClass)
    {
        $flow = app($flowClass);
        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        return $flow;
    }

    public function changeStatus($status)
    {
        app()->call('App\Http\Controllers\UserStateController@updateState', [
            'id' => $this->user->id,
            'status' => $status
        ]);
    }
}
