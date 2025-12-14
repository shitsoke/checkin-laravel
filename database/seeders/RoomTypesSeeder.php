<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypesSeeder extends Seeder
{
    public function run()
    {
        RoomType::updateOrCreate(
            ['name' => 'Standard Room'],
            ['hourly_rate' => 150.00]
        );

        RoomType::updateOrCreate(
            ['name' => 'Deluxe Room'],
            ['hourly_rate' => 250.00]
        );

        RoomType::updateOrCreate(
            ['name' => 'Suite Room'],
            ['hourly_rate' => 400.00]
        );
    }
}

