<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CompanySeeder::class,
            UserSeeder::class,
            CitySeeder::class,
            CommentSeeder::class,
            RoomSeeder::class,
            ReservationSeeder::class,
            AddressSeeder::class,
            CityRoomSeeder::class,
            ImageSeeder::class,
            LikeablesSeeder::class,
        ]);
    }
}
