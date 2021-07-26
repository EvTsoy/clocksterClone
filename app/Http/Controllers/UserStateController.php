<?php

namespace App\Http\Controllers;

use App\Models\State;

class UserStateController extends Controller
{
    public function store($state)
    {
        return State::updateOrCreate([
            'user_id' => $state->user_id
        ], $state);
    }
}
