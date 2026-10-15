<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserManagement\Permission;
use App\Models\UserManagement\Role;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Permissions ────────────────────────────────────────
        $permissions = [
            // Master Data — Vehicle Types
            ['group' => 'master-data.vehicle-types', 'slug' => 'master-data.vehicle-types.monitor', 'name' => 'Lihat Daftar Tipe Kendaraan'],
            ['group' => 'master-data.vehicle-types', 'slug' => 'master-data.vehicle-types.view',    'name' => 'Lihat Detail Tipe Kendaraan'],
            ['group' => 'master-data.vehicle-types', 'slug' => 'master-data.vehicle-types.create',  'name' => 'Tambah Tipe Kendaraan'],
            ['group' => 'master-data.vehicle-types', 'slug' => 'master-data.vehicle-types.update',  'name' => 'Ubah Tipe Kendaraan'],
            ['group' => 'master-data.vehicle-types', 'slug' => 'master-data.vehicle-types.delete',  'name' => 'Hapus Tipe Kendaraan'],

            // Master Data — Vehicle Brands
            ['group' => 'master-data.vehicle-brands', 'slug' => 'master-data.vehicle-brands.monitor', 'name' => 'Lihat Daftar Merek Kendaraan'],
            ['group' => 'master-data.vehicle-brands', 'slug' => 'master-data.vehicle-brands.view',    'name' => 'Lihat Detail Merek Kendaraan'],
            ['group' => 'master-data.vehicle-brands', 'slug' => 'master-data.vehicle-brands.create',  'name' => 'Tambah Merek Kendaraan'],
            ['group' => 'master-data.vehicle-brands', 'slug' => 'master-data.vehicle-brands.update',  'name' => 'Ubah Merek Kendaraan'],
            ['group' => 'master-data.vehicle-brands', 'slug' => 'master-data.vehicle-brands.delete',  'name' => 'Hapus Merek Kendaraan'],

            // Master Data — Vehicle Models
            ['group' => 'master-data.vehicle-models', 'slug' => 'master-data.vehicle-models.monitor', 'name' => 'Lihat Daftar Model Kendaraan'],
            ['group' => 'master-data.vehicle-models', 'slug' => 'master-data.vehicle-models.view',    'name' => 'Lihat Detail Model Kendaraan'],
            ['group' => 'master-data.vehicle-models', 'slug' => 'master-data.vehicle-models.create',  'name' => 'Tambah Model Kendaraan'],
            ['group' => 'master-data.vehicle-models', 'slug' => 'master-data.vehicle-models.update',  'name' => 'Ubah Model Kendaraan'],
            ['group' => 'master-data.vehicle-models', 'slug' => 'master-data.vehicle-models.delete',  'name' => 'Hapus Model Kendaraan'],

            // Master Data — Fuel Types
            ['group' => 'master-data.fuel-types', 'slug' => 'master-data.fuel-types.monitor', 'name' => 'Lihat Daftar Bahan Bakar'],
            ['group' => 'master-data.fuel-types', 'slug' => 'master-data.fuel-types.create',  'name' => 'Tambah Bahan Bakar'],
            ['group' => 'master-data.fuel-types', 'slug' => 'master-data.fuel-types.update',  'name' => 'Ubah Bahan Bakar'],
            ['group' => 'master-data.fuel-types', 'slug' => 'master-data.fuel-types.delete',  'name' => 'Hapus Bahan Bakar'],

            // Master Data — Body Types
            ['group' => 'master-data.body-types', 'slug' => 'master-data.body-types.monitor', 'name' => 'Lihat Daftar Jenis Karoseri'],
            ['group' => 'master-data.body-types', 'slug' => 'master-data.body-types.create',  'name' => 'Tambah Jenis Karoseri'],
            ['group' => 'master-data.body-types', 'slug' => 'master-data.body-types.update',  'name' => 'Ubah Jenis Karoseri'],
            ['group' => 'master-data.body-types', 'slug' => 'master-data.body-types.delete',  'name' => 'Hapus Jenis Karoseri'],

            // Data Management — Fleet Data
            ['group' => 'data-management.fleet-data', 'slug' => 'data-management.fleet-data.monitor', 'name' => 'Lihat Daftar Kendaraan'],
            ['group' => 'data-management.fleet-data', 'slug' => 'data-management.fleet-data.view',    'name' => 'Lihat Detail Kendaraan'],
            ['group' => 'data-management.fleet-data', 'slug' => 'data-management.fleet-data.create',  'name' => 'Tambah Kendaraan'],
            ['group' => 'data-management.fleet-data', 'slug' => 'data-management.fleet-data.update',  'name' => 'Ubah Kendaraan'],
            ['group' => 'data-management.fleet-data', 'slug' => 'data-management.fleet-data.delete',  'name' => 'Hapus Kendaraan'],
            ['group' => 'data-management.fleet-data', 'slug' => 'data-management.fleet-data.toggle',  'name' => 'Toggle Status Kendaraan'],

            // SDM — Driver Data
            ['group' => 'data-management.sdm-management.driver-data', 'slug' => 'data-management.sdm-management.driver-data.monitor', 'name' => 'Lihat Daftar Supir'],
            ['group' => 'data-management.sdm-management.driver-data', 'slug' => 'data-management.sdm-management.driver-data.view',    'name' => 'Lihat Detail Supir'],
            ['group' => 'data-management.sdm-management.driver-data', 'slug' => 'data-management.sdm-management.driver-data.create',  'name' => 'Tambah Supir'],
            ['group' => 'data-management.sdm-management.driver-data', 'slug' => 'data-management.sdm-management.driver-data.update',  'name' => 'Ubah Supir'],
            ['group' => 'data-management.sdm-management.driver-data', 'slug' => 'data-management.sdm-management.driver-data.delete',  'name' => 'Hapus Supir'],

            // SDM — Employee Data
            ['group' => 'data-management.sdm-management.employee-data', 'slug' => 'data-management.sdm-management.employee-data.monitor', 'name' => 'Lihat Daftar Karyawan'],
            ['group' => 'data-management.sdm-management.employee-data', 'slug' => 'data-management.sdm-management.employee-data.view',    'name' => 'Lihat Detail Karyawan'],
            ['group' => 'data-management.sdm-management.employee-data', 'slug' => 'data-management.sdm-management.employee-data.create',  'name' => 'Tambah Karyawan'],
            ['group' => 'data-management.sdm-management.employee-data', 'slug' => 'data-management.sdm-management.employee-data.update',  'name' => 'Ubah Karyawan'],
            ['group' => 'data-management.sdm-management.employee-data', 'slug' => 'data-management.sdm-management.employee-data.delete',  'name' => 'Hapus Karyawan'],

            // User Management — Users
            ['group' => 'user-management.users', 'slug' => 'user-management.users.monitor', 'name' => 'Lihat Daftar User'],
            ['group' => 'user-management.users', 'slug' => 'user-management.users.view',    'name' => 'Lihat Detail User'],
            ['group' => 'user-management.users', 'slug' => 'user-management.users.create',  'name' => 'Tambah User'],
            ['group' => 'user-management.users', 'slug' => 'user-management.users.update',  'name' => 'Ubah User'],
            ['group' => 'user-management.users', 'slug' => 'user-management.users.delete',  'name' => 'Hapus User'],

            // User Management — Roles
            ['group' => 'user-management.roles', 'slug' => 'user-management.roles.monitor', 'name' => 'Lihat Daftar Role'],
            ['group' => 'user-management.roles', 'slug' => 'user-management.roles.create',  'name' => 'Tambah Role'],
            ['group' => 'user-management.roles', 'slug' => 'user-management.roles.update',  'name' => 'Kelola Permission Role'],
            ['group' => 'user-management.roles', 'slug' => 'user-management.roles.delete',  'name' => 'Hapus Role'],

            // User Management — Permissions
            ['group' => 'user-management.permissions', 'slug' => 'user-management.permissions.monitor', 'name' => 'Lihat Daftar Permission'],
            ['group' => 'user-management.permissions', 'slug' => 'user-management.permissions.create',  'name' => 'Tambah Permission'],
            ['group' => 'user-management.permissions', 'slug' => 'user-management.permissions.update',  'name' => 'Ubah Permission'],
            ['group' => 'user-management.permissions', 'slug' => 'user-management.permissions.delete',  'name' => 'Hapus Permission'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['slug' => $perm['slug']],
                ['name' => $perm['name'], 'group' => $perm['group']]
            );
        }

        $this->command->info('✓ ' . count($permissions) . ' permissions selesai.');

        // ── 2. Roles ──────────────────────────────────────────────
        $allPermissionIds = Permission::pluck('id')->toArray();
        $monitorViewIds   = Permission::where('slug', 'like', '%.monitor')
                               ->orWhere('slug', 'like', '%.view')
                               ->pluck('id')->toArray();

        // Superadmin — semua permission
        $superadmin = Role::firstOrCreate(
            ['slug' => 'superadmin'],
            ['name' => 'Super Admin', 'description' => 'Akses penuh ke seluruh sistem']
        );
        $superadmin->permissions()->sync($allPermissionIds);

        // Admin — semua permission
        $admin = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Akses penuh ke semua data']
        );
        $admin->permissions()->sync($allPermissionIds);

        // Operator — monitor & view saja
        $operator = Role::firstOrCreate(
            ['slug' => 'operator'],
            ['name' => 'Operator', 'description' => 'Hanya dapat melihat data']
        );
        $operator->permissions()->sync($monitorViewIds);

        $this->command->info('✓ 3 roles selesai (superadmin, admin, operator).');

        // ── 3. Assign user id=1 ke role Superadmin ────────────────
        $user = User::find(1);

        if ($user) {
            $user->roles()->syncWithoutDetaching([$superadmin->id]);
            $this->command->info("✓ User [{$user->full_name}] di-assign ke role Superadmin.");
        } else {
            $this->command->warn('⚠ User id=1 tidak ditemukan, skip assign role.');
        }
    }
}