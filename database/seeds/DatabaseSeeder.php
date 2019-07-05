<?php

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
        // // Procuction calls
        // $this->call(RolesAndPermissionsSeeder::class);
        // $this->call(UsersTableSeeder::class);

        // Development calls
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UsersDummyTableSeeder::class);
        $this->call(BookmarksTableSeeder::class);
    }
}
