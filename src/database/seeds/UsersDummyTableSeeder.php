<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Profile;

class UsersDummyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the administrator
        $adminUser = User::create([
            'name'		=> 'Admin',
            'email'		=> 'admin@crosslink.com',
            'password'	=> bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Assign the admin role to Admin
        $adminUser->assignRole('admin');

        // Create and assign a profile to the Admin
        $profile = factory('App\Profile')->make()->getAttributes();
        Profile::create($profile + ['user_id' => $adminUser->id]);

        // Create the verified user-admin
        $user = User::create([
        	'name'		=> 'UserAdmin',
        	'email'		=> 'user-admin@crosslink.com',
            'password'	=> bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Assign the user role to user-admin
        $user->assignRole('user-admin');

        // Create and assign a profile to the user-admin
        $profile = factory('App\Profile')->make()->getAttributes();
        Profile::create($profile + ['user_id' => $user->id]);

        // Create a standard user
        $user = User::create([
            'name'		=> 'User',
            'email'		=> 'user@crosslink.com',
            'password'	=> bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Assign the user a standard user role.
        $user->assignRole('user');

        // Create and assign a profile to the user
        $profile = factory('App\Profile')->make()->getAttributes();
        Profile::create($profile + ['user_id' => $user->id]);

        // Create a non-verified user
        $user = User::create([
            'name'      => 'User Unverified',
            'email'     => 'useruv@crosslink.com',
            'password'  => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Assign the admin role to user
        $user->assignRole('user');

        // Create and assign a profile to the user
        $profile = factory('App\Profile')->make()->getAttributes();
        Profile::create($profile + ['user_id' => $user->id]);

        // Generate 16 dummy users for testing the users section of the website
        factory('App\User', 30)->create()
            ->each(function (App\User $user) {
                $profile = factory('App\Profile')->make()->getAttributes();
                App\Profile::create($profile + ['user_id' => $user->id]);
            });
    }
}
