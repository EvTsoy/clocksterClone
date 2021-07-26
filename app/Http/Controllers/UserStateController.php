<?php

namespace App\Http\Controllers;

use App\Models\State;
use Log;

class UserStateController extends Controller
{
    public function store($values)
    {

        Log::debug('UserStateController.values', [
            'values' => $values,
        ]);
        return State::updateOrCreate([
            'user_id' => $values['user_id']
        ], $values);
    }
}
