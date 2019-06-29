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
        // Create the verified user
        $user = App\User::create([
        	'name'		=> 'AdminUser',
        	'email'		=> 'admin-user@crosslink.com',
            'password'	=> bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Assign the user role to Admin
        $user->assignRole('admin user');

        // Create and assign a profile to the verified user
        $profile = factory('App\Profile')->make()->getAttributes();
        App\Profile::create($profile + ['user_id' => $user->id]);

        // Create the unverified user
        $user = App\User::create([
            'name'		=> 'User',
            'email'		=> 'user@crosslink.com',
            'password'	=> bcrypt('password'),
        ]);
        
        // Assign the admin role to user
        $user->assignRole('user');
        
        // Create and assign a profile to the user
        $profile = factory('App\Profile')->make()->getAttributes();
        App\Profile::create($profile + ['user_id' => $user->id]);

        // Generate 16 dummy users for testing the users section of the website
        factory('App\User', 30)->create()
            ->each(function (App\User $user) {
                $profile = factory('App\Profile')->make()->getAttributes();
                App\Profile::create($profile + ['user_id' => $user->id]);
            });
    }
}
