<?php

namespace App\Conversation;

use App\Conversation\Flows\AbstractFlow;
use App\Models\User;
use Log;
use Cache;

class Context
{
    public function save(User $user, AbstractFlow $flow, string $state)
    {
        Log::debug('Context.save', [
            'user' => $user->toArray(),
            'message' => get_class($flow),
            'state' => $state
        ]);

        Cache::forever($this->key($user), [
            'flow' => get_class($flow),
            'state' => $state,
        ]);
    }

    private function key(User $user)
    {
        return 'context_' . $user->id;
    }
}
