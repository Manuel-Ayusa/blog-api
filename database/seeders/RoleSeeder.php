<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        $admin = Role::create(['name' => 'admin']);

        $create_post = Permission::create(['name' => 'create posts']);
        $edit_post = Permission::create(['name' => 'edit posts']);
        $delete_post = Permission::create(['name' => 'delete posts']);  

        $admin->syncPermissions([$create_post, $edit_post, $delete_post]);
    }
}
