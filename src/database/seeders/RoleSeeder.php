<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем роли
        $superAdmin = Role::create(['name' => 'super-admin']);
        $adminRead = Role::create(['name' => 'admin-read']);
        $adminWrite = Role::create(['name' => 'admin-write']);

        // Создаем права доступа
        Permission::create(['name' => 'view-dashboard']);
        Permission::create(['name' => 'manage-cash']);
        Permission::create(['name' => 'manage-employees']);
        Permission::create(['name' => 'manage-payroll']);
        Permission::create(['name' => 'manage-users']);
        Permission::create(['name' => 'manage-settings']);

        // Назначаем права ролям
        $superAdmin->givePermissionTo(Permission::all());
        $adminRead->givePermissionTo(['view-dashboard']);
        $adminWrite->givePermissionTo([
            'view-dashboard',
            'manage-cash',
            'manage-employees',
            'manage-payroll',
        ]);
    }
}
