<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = array(
            'Нур-Султан',
            'Алматы',
            'Шымкент',
            'Караганда',
            'Актобе',
            'Тараз',
            'Павлодар',
            'Усть-Каменогорск',
            'Семей',
            'Атырау',
            'Костанай',
            'Кызылорда',
            'Уральск',
            'Петропавловск',
            'Темиртау',
            'Актау',
            'Туркестан',
            'Кокшетау',
            'Талдыкорган',
            'Экибастуз',
            'Рудный',
        );

        foreach ($cities as $city) {
            City::create([
                 'name' => $city
            ]);
        }
    }
}
