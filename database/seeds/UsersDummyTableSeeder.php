<?php

use Illuminate\Database\Seeder;

class UsersDummyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate 16 dummy users for testing the users section of the website
        factory('App\User', 16)->create()
            ->each(function (App\User $user) {
                $profile = factory('App\Profile')->make()->getAttributes();
                App\Profile::create($profile + ['user_id' => $user->id]);
            });
    }
}
