<?php

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
        factory(\App\User::class, 1000)->create()->each(function ($user) {
            /**
             * @var \App\User $user
             */

            $user->todos()->saveMany(factory(\App\Models\Todo::class, 3)->make());
        });
    }
}
