<?php

namespace App\Conversation\Flows;

use App\Models\User;

abstract class AbstractConversation
{
    public function getStatus(User $user)
    {
        //  Стаус пользователя
        $state = app()->call('App\Http\Controllers\UserStateController@show', [
            'id' => $user->id
        ]);

        // Если статуса нет
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

}
