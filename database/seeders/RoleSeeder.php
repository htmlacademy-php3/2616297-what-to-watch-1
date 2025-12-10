<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Заполняет базу данных данными о ролях и разрешениями
 */
final class RoleSeeder extends Seeder
{
    /**
     * @return void
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'edit genre']);
        Permission::create(['name' => 'edit comments']);
        Permission::create(['name' => 'remove comments']);
        Permission::create(['name' => 'publish films']);
        Permission::create(['name' => 'edit films']);
        Permission::create(['name' => 'set promo film']);

        Role::create(['name' => 'moderator'])
            ->givePermissionTo(
                ['edit genre', 'edit comments', 'remove comments', 'publish films', 'edit films']
            );
    }
}