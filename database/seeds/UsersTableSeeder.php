<?php

use Illuminate\Database\Seeder;

use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = User::create([
        	'name'		=> 'Admin',
        	'email'		=> 'admin@mail.com',
        	'password'	=> bcrypt('secret'),
        ]);

        $adminUser->assignRole('admin');
    }
}