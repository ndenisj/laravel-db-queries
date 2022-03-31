<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = Country::factory(10)
                        ->create()
                        ->each(function($country){
                            $country->cities()->saveMany(
                                City::factory(mt_rand(1,10))->make()
                            );
                        });


       foreach($countries as $country){
           foreach($country->cities as $city){
               $hotels = $city->hotels()->saveMany(Hotel::factory(mt_rand(1, 4))->make());

               foreach($hotels as $hotel){
                   $hotel->rooms()->saveMany(Room::factory(mt_rand(3, 10))->make());
               }
           }
       }
    }
}
