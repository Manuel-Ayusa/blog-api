<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Restablece roles y permisos almacenados en caché
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::create(['name' => 'Admin']);
        $blogger = Role::create(['name' => 'Blogger']);

        $create_post = Permission::create(['name' => 'create posts']);
        $edit_post = Permission::create(['name' => 'edit posts']);
        $delete_post = Permission::create(['name' => 'delete posts']);  

        $create_category = Permission::create(['name' => 'create categories']);
        $edit_category = Permission::create(['name' => 'edit categories']);
        $delete_category = Permission::create(['name' => 'delete categories']);  

        $create_tag = Permission::create(['name' => 'create tags']);
        $edit_tag = Permission::create(['name' => 'edit tags']);
        $delete_tag = Permission::create(['name' => 'delete tags']);  

        $update_users = Permission::create(['name' => 'update users']);  

        $admin->syncPermissions([$create_post, $edit_post, $delete_post, $create_category, $edit_category, $delete_category, $create_tag, $edit_tag, $delete_tag, $update_users]);

        $blogger->syncPermissions([$create_post, $edit_post, $delete_post]);
    }
}
