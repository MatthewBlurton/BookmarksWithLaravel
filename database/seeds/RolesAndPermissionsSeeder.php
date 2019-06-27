<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // User privelages
        Permission::create(['name' => 'browse users']);
        Permission::create(['name' => 'read users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'add users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'search users']);

        // Tag privelages
        Permission::create(['name' => 'browse tags']);
        Permission::create(['name' => 'read tags']);
        Permission::create(['name' => 'edit tags']);
        Permission::create(['name' => 'add tags']);
        Permission::create(['name' => 'delete tags']);
        Permission::create(['name' => 'search tags']);

        // Bookmark privelages
        Permission::create(['name' => 'browse bookmarks']);
        Permission::create(['name' => 'read bookmarks']);
        Permission::create(['name' => 'edit bookmarks']);
        Permission::create(['name' => 'add bookmarks']);
        Permission::create(['name' => 'delete bookmarks']);
        Permission::create(['name' => 'search bookmarks']);

        // Profile privelages
        Permission::create(['name' => 'browse profiles']);
        Permission::create(['name' => 'read profiles']);
        Permission::create(['name' => 'edit profiles']);
        Permission::create(['name' => 'add profiles']);
        Permission::create(['name' => 'delete profiles']);
        Permission::create(['name' => 'search profiles']);


        // Admin level privelages
        Permission::create(['name' => 'bread ordinary users']);
        Permission::create(['name' => 'breads all users']);
        Permission::create(['name' => 'breads all tags']);
        Permission::create(['name' => 'breads all bookmarks']);
        Permission::create(['name' => 'breads all profiles']);


        // Bundle permissions together
        // Users
        $usersEdBundle = ['edit users', 'delete users'];

        // Tags
        $tagsEadBundle = [
            'edit tags',
            'add tags',
            'delete tags'
        ];

        // Bookmarks
        $bookmarksEadBundle = [
            'edit bookmarks',
            'add bookmarks',
            'delete bookmarks'
        ];

        $profilesBreadsBundle = [
            'browse profiles',
            'read profiles',
            'edit profiles',
            'add profiles',
            'delete profiles',
            'search profiles'
        ];


        // Create roles and assign created permissions
        Role::create(['name' => 'suspended']);
        Role::create(['name' => 'user'])
            ->givePermissionTo($tagsEadBundle)
            ->givePermissionTo($bookmarksEadBundle)
            ->givePermissionTo($profilesBreadsBundle);
        Role::create(['name' => 'user-admin'])
            ->givePermissionTo($usersEdBundle)
            ->givePermissionTo($profilesBreadsBundle)
            ->givePermissionTo([
                'bread ordinary users',
                'breads all profiles',
            ]);
        Role::create(['name' => 'admin'])
            ->givePermissionTo($usersEdBundle)
            ->givePermissionTo($tagsEadBundle)
            ->givePermissionTo($bookmarksEadBundle)
            ->givePermissionTo($profilesBreadsBundle)
            ->givePermissionTo([
                'breads all users',
                'breads all tags',
                'breads all bookmarks',
                'breads all profiles',
            ]);
    }
}
