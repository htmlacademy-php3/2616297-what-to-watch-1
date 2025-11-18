<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'edit genre']);
        Permission::create(['name' => 'edit comments']);
        Permission::create(['name' => 'remove comments']);
        Permission::create(['name' => 'publish films']);
        Permission::create(['name' => 'edit films']);

        Role::create(['name' => 'moderator'])
            ->givePermissionTo(
                ['edit genre', 'edit comments', 'remove comments', 'publish films', 'edit films']
            );
    }
}