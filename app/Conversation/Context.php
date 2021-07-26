<?php

namespace App\Conversation;

use App\Conversation\Flows\AbstractFlow;
use App\Models\User;
use Log;

class Context
{
    public static function save(User $user, AbstractFlow $flow, string $state)
    {
        Log::debug('Context.save', [
            'user' => $user->toArray(),
            'message' => get_class($flow),
            'state' => $state
        ]);

        app()->call('App\Http\Controllers\UserStateController@store', [
            'values' => [
                'user_id' => $user->id,
                'flow' => get_class($flow),
                'state' => $state,
            ]
        ]);
    }

    public static function get(User $user)
    {
        return app()->call('App\Http\Controllers\UserStateController@show', [
                'id' => $user->id,
            ]);
    }
}
