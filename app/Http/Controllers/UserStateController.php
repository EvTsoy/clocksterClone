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
        ], [
            'user_id' => $values['user_id'],
            'state' => $values['state'],
            'flow' => $values['flow']
        ]);
    }

    public function show($id)
    {
        return State::where('id', $id)->first();
    }

    public function updateState($id, $status)
    {
        $state = State::where('user_id', $id);

        return $state->update([
            'status' => $status
        ]);
    }
}
