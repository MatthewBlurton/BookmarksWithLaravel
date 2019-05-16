<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
        	'name'			=> 'user',
        	'description'	=> 'Standard user, has access to their own bookmarks, and their own profile',
        ]);

        DB::table('roles')->insert([
        	'name'			=> 'admin',
        	'description'	=> 'Administrator, has access to all bookmarks, website statistics, and account profiles',
        ]);
    }
}
