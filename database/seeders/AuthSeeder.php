<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserManagement\Permission;
use App\Models\UserManagement\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Buat Role Superadmin ──────────────────────────
        $superadmin = Role::create([
            'name'        => 'Superadmin',
            'slug'        => 'superadmin',
            'description' => 'Akses penuh ke seluruh sistem',
        ]);

        // ── 2. Buat Permissions ──────────────────────────────
        $permissions = [
            // User Management
            ['name' => 'Monitor User',   'slug' => 'users.monitor',  'group' => 'users'],
            ['name' => 'Detail User',    'slug' => 'users.view',     'group' => 'users'],
            ['name' => 'Tambah User',    'slug' => 'users.create',   'group' => 'users'],
            ['name' => 'Ubah User',      'slug' => 'users.update',   'group' => 'users'],
            ['name' => 'Hapus User',     'slug' => 'users.delete',   'group' => 'users'],

            // Role Management
            ['name' => 'Monitor Role',   'slug' => 'roles.monitor',  'group' => 'roles'],
            ['name' => 'Detail Role',    'slug' => 'roles.view',     'group' => 'roles'],
            ['name' => 'Tambah Role',    'slug' => 'roles.create',   'group' => 'roles'],
            ['name' => 'Ubah Role',      'slug' => 'roles.update',   'group' => 'roles'],
            ['name' => 'Hapus Role',     'slug' => 'roles.delete',   'group' => 'roles'],

            // Permission Management
            ['name' => 'Monitor Permission', 'slug' => 'permissions.monitor', 'group' => 'permissions'],
            ['name' => 'Detail Permission',  'slug' => 'permissions.view',    'group' => 'permissions'],
            ['name' => 'Tambah Permission',  'slug' => 'permissions.create',  'group' => 'permissions'],
            ['name' => 'Ubah Permission',    'slug' => 'permissions.update',  'group' => 'permissions'],
            ['name' => 'Hapus Permission',   'slug' => 'permissions.delete',  'group' => 'permissions'],
        ];

        foreach ($permissions as $perm) {
            Permission::create($perm);
        }

        // ── 3. Assign semua permission ke Superadmin ─────────
        $superadmin->permissions()->attach(Permission::pluck('id'));

        // ── 4. Buat akun Superadmin ───────────────────────────
        User::create([
            'employee_code'      => 'EMP-SUPERADMIN',
            'employee_id_number' => '1234567890',
            'full_name'          => 'SUPERADMIN',
            'department'         => 'SUPERADMIN',
            'position'           => 'System Administrator',
            'work_location'      => 'Head Office',
            'password'           => Hash::make('qlogic'),
            'role_id'            => $superadmin->id,
        ]);
    }
}