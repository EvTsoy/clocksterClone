<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($user)
    {
        $values = [
            'user_telegram_id' => $user->id,
            'first_name' => $user->firstName,
            'last_name' => $user->lastName,
            'username' => $user->username
        ];

        return User::firstOrCreate([
            'user_telegram_id' => $user->id
        ], $values);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::where('user_telegram_id', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($name, $id)
    {
        $user = User::findOrFail($id);
        return $user->update([
            'first_name' => $name
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updatePhone($id, $phoneNumber)
    {
        $user = User::findOrFail($id);
        if (isset($phoneNumber) && $phoneNumber !== '') {
            $user->update([
                'phone_number' => $phoneNumber
            ]);
        }

        return $user;
    }

    public function updateCity($id, $city)
    {
        $user = User::findOrFail($id);
        return $user->update([
            'city' => $city
        ]);
    }

    public function updateDateOfBirth($id, $dateOfBirth)
    {
        $user = User::findOrFail($id);
        return $user->update([
            'date_of_birth' => $dateOfBirth
        ]);
    }
}
