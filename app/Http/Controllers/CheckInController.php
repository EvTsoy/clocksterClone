<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Models\User;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function store($id, $date, $location)
    {
        $user = User::findOrFail($id);
        if (isset($location)) {
             $checkin = CheckIn::create([
                'user_id' => $user->id,
                'lat' => $location['latitude'],
                'lng' => $location['longitude'],
                'time' => $date
            ]);
             return $checkin;
        }
        return false;
    }
}
