<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(5)->create()
        // ->each(function ($user) {
        //     $user->address()->save(
        //         \App\Models\Address::factory()->make()
        //     );
        // });

        \App\Models\User::factory(2000)->create();

        // $connection = 'sqlite';
        // $users = \App\Models\User::factory(3)->make();
        // $users->each(function($model) use($connection) {
        //     $model->setConnection($connection);
        //     $model->save();
        // });
    }
}
