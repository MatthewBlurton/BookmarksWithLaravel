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

        // Create permissions
        Permission::create(['name' => 'browse users']);
        Permission::create(['name' => 'read users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'add users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'search users']);
        Permission::create(['name' => 'reset password']);

        Permission::create(['name' => 'browse tags']);
        Permission::create(['name' => 'read tags']);
        Permission::create(['name' => 'edit tags']);
        Permission::create(['name' => 'add tags']);
        Permission::create(['name' => 'delete tags']);
        Permission::create(['name' => 'search tags']);

        Permission::create(['name' => 'browse bookmarks']);
        Permission::create(['name' => 'read bookmarks']);
        Permission::create(['name' => 'edit bookmarks']);
        Permission::create(['name' => 'add bookmarks']);
        Permission::create(['name' => 'delete bookmarks']);
        Permission::create(['name' => 'search bookmarks']);

        Permission::create(['name' => 'browse profiles']);
        Permission::create(['name' => 'read profiles']);
        Permission::create(['name' => 'edit profiles']);
        Permission::create(['name' => 'add profiles']);
        Permission::create(['name' => 'delete profiles']);
        Permission::create(['name' => 'search profiles']);

        Permission::create(['name' => 'limited actions on users only']);
        Permission::create(['name' => 'actions on all users']);
        Permission::create(['name' => 'actions on all tags']);
        Permission::create(['name' => 'actions on all bookmarks']);
        Permission::create(['name' => 'actions on all profiles']);


        // Bundle permissions together
        // Users
        $usersBrsBundle = [
            'browse users',
            'read users',
            'search users'
        ];
        $usersBreadsBundle = $usersBrsBundle;
        array_push($usersBreadsBundle, 'edit users', 'add users', 'delete users');

        // Tags
        $tagsBrsBundle = [
            'browse users',
            'read users',
            'search users'
        ];
        $tagsBrasBundle = $tagsBrsBundle;
        array_push($tagsBrasBundle, 'add tags');

        $tagsBreadsBundle = $tagsBrsBundle;
        array_push($tagsBreadsBundle, 'edit tags', 'add tags', 'delete tags');

        // Bookmarks
        $bookmarksBrsBundle = [
            'browse bookmarks',
            'read bookmarks',
            'search bookmarks'
        ];
        $bookmarksBreadsBundle = $bookmarksBrsBundle;
        array_push($bookmarksBreadsBundle, 'edit bookmarks', 'add bookmarks', 'delete bookmarks');

        $profilesBreadsBundle = [
            'browse profiles',
            'read profiles',
            'edit profiles',
            'add profiles',
            'delete profiles',
            'search profiles'
        ];


        // Create roles and assign created permissions
        Role::create(['name' => 'suspended'])
            ->givePermissionTo('reset password');
        Role::create(['name' => 'guest'])
            ->givePermissionTo($usersBrsBundle)
            ->givePermissionTo($tagsBrsBundle)
            ->givePermissionTo($bookmarksBrsBundle);
        Role::create(['name' => 'user'])
            ->givePermissionTo($usersBrsBundle)
            ->givePermissionTo($tagsBreadsBundle)
            ->givePermissionTo($bookmarksBreadsBundle)
            ->givePermissionTo($profilesBreadsBundle);
        Role::create(['name' => 'user-admin'])
            ->givePermissionTo($usersBreadsBundle)
            ->givePermissionTo($profilesBreadsBundle)
            ->givePermissionTo('limited actions on users only');
        Role::create(['name' => 'admin'])
            ->givePermissionTo($usersBreadsBundle)
            ->givePermissionTo($tagsBreadsBundle)
            ->givePermissionTo($bookmarksBreadsBundle)
            ->givePermissionTo($profilesBreadsBundle)
            ->givePermissionTo([
                'actions on all users',
                'actions on all tags',
                'actions on all bookmarks',
                'actions on all profiles',
            ]);
    }
}
