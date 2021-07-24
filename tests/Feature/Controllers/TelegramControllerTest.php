<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TelegramControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function store_test()
    {
        app()->call('App\Http\Controllers\UserController@store', [
                'user_telegram_id' => 1,
                'first_name' => 'a',
                'last_name' => 'b',
                'username' => 'c'
            ]
        );
    }
}
