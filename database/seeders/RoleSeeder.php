<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache dulu
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'report.create',
            'report.view-own',
            'report.view-all',
            'report.view-private',
            'report.update-status',
            'report.delete',
            'report.assign',
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'user.assign-role',
            'category.manage',
            'status.manage',
            'dashboard.warga',
            'dashboard.petugas',
            'dashboard.admin',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Role WARGA
        $warga = Role::firstOrCreate(['name' => 'warga']);
        $warga->syncPermissions([
            'report.create',
            'report.view-own',
            'dashboard.warga',
        ]);

        // Role PETUGAS
        $petugas = Role::firstOrCreate(['name' => 'petugas']);
        $petugas->syncPermissions([
            'report.create',
            'report.view-own',
            'report.view-all',
            'report.update-status',
            'dashboard.warga',
            'dashboard.petugas',
        ]);

        // Role ADMIN — dapat semua permission
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);
    }
}
