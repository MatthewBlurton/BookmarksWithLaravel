<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Profile;

class UsersTableSeeder extends Seeder
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
        	'name'		=> 'Root',
        	'email'		=> 'root@crosslink.com',
            'password'	=> bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Assign the admin role to Admin
        $adminUser->assignRole('root');

        // Create and assign a profile to the Admin
        $profile = factory('App\Profile')->make()->getAttributes();
        Profile::create($profile + ['user_id' => $adminUser->id]);
    }
}
