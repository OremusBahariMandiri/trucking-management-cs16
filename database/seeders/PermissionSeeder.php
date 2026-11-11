<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserManagement\Permission;
use App\Models\UserManagement\Role;
use Illuminate\Database\Seeder;

/**
 */
class PermissionSeeder extends Seeder
{
    private array $permissions = [
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-types.index', 'name' => 'Lihat Daftar Jenis Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-types.create', 'name' => 'Tambah Jenis Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-types.edit', 'name' => 'Ubah Jenis Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-types.delete', 'name' => 'Hapus Jenis Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-brands.index', 'name' => 'Lihat Daftar Merek Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-brands.create', 'name' => 'Tambah Merek Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-brands.edit', 'name' => 'Ubah Merek Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-brands.delete', 'name' => 'Hapus Merek Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-models.index', 'name' => 'Lihat Daftar Model Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-models.create', 'name' => 'Tambah Model Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-models.edit', 'name' => 'Ubah Model Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.vehicle-models.delete', 'name' => 'Hapus Model Kendaraan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.fuel-types.index', 'name' => 'Lihat Daftar Jenis BBM'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.fuel-types.create', 'name' => 'Tambah Jenis BBM'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.fuel-types.edit', 'name' => 'Ubah Jenis BBM'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.fuel-types.delete', 'name' => 'Hapus Jenis BBM'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.body-types.index', 'name' => 'Lihat Daftar Jenis Karoseri'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.body-types.create', 'name' => 'Tambah Jenis Karoseri'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.body-types.edit', 'name' => 'Ubah Jenis Karoseri'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.body-types.delete', 'name' => 'Hapus Jenis Karoseri'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.axis-configurations.index', 'name' => 'Lihat Daftar Konfigurasi Sumbu'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.axis-configurations.create', 'name' => 'Tambah Konfigurasi Sumbu'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.axis-configurations.edit', 'name' => 'Ubah Konfigurasi Sumbu'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.axis-configurations.delete', 'name' => 'Hapus Konfigurasi Sumbu'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.ownership-statuses.index', 'name' => 'Lihat Daftar Status Kepemilikan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.ownership-statuses.create', 'name' => 'Tambah Status Kepemilikan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.ownership-statuses.edit', 'name' => 'Ubah Status Kepemilikan'],
        ['group' => 'Master Data > Kendaraan', 'slug' => 'master-data.ownership-statuses.delete', 'name' => 'Hapus Status Kepemilikan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.customer-types.index', 'name' => 'Lihat Daftar Jenis Pelanggan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.customer-types.create', 'name' => 'Tambah Jenis Pelanggan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.customer-types.edit', 'name' => 'Ubah Jenis Pelanggan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.customer-types.delete', 'name' => 'Hapus Jenis Pelanggan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.business-types.index', 'name' => 'Lihat Daftar Jenis Usaha'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.business-types.create', 'name' => 'Tambah Jenis Usaha'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.business-types.edit', 'name' => 'Ubah Jenis Usaha'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.business-types.delete', 'name' => 'Hapus Jenis Usaha'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.customers.index', 'name' => 'Lihat Daftar Pelanggan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.customers.show', 'name' => 'Lihat Detail Pelanggan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.customers.create', 'name' => 'Tambah Pelanggan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.customers.edit', 'name' => 'Ubah Pelanggan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.customers.delete', 'name' => 'Hapus Pelanggan'],
        ['group' => 'Data Management > Armada', 'slug' => 'data-management.fleet-data.index', 'name' => 'Lihat Daftar Armada'],
        ['group' => 'Data Management > Armada', 'slug' => 'data-management.fleet-data.show', 'name' => 'Lihat Detail Armada'],
        ['group' => 'Data Management > Armada', 'slug' => 'data-management.fleet-data.create', 'name' => 'Tambah Armada'],
        ['group' => 'Data Management > Armada', 'slug' => 'data-management.fleet-data.edit', 'name' => 'Ubah Armada'],
        ['group' => 'Data Management > Armada', 'slug' => 'data-management.fleet-data.delete', 'name' => 'Hapus Armada'],
        ['group' => 'Data Management > Armada', 'slug' => 'data-management.fleet-data.export-pdf', 'name' => 'Export PDF Armada'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.driver-data.index', 'name' => 'Lihat Daftar Supir'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.driver-data.show', 'name' => 'Lihat Detail Supir'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.driver-data.create', 'name' => 'Tambah Supir'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.driver-data.edit', 'name' => 'Ubah Data Supir'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.driver-data.delete', 'name' => 'Hapus Supir'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.driver-data.export-pdf', 'name' => 'Export CV PDF Supir'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.employee-data.index', 'name' => 'Lihat Daftar Karyawan'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.employee-data.show', 'name' => 'Lihat Detail Karyawan'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.employee-data.create', 'name' => 'Tambah Karyawan'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.employee-data.edit', 'name' => 'Ubah Data Karyawan'],
        ['group' => 'Data Management > SDM', 'slug' => 'data-management.sdm-management.employee-data.delete', 'name' => 'Hapus Karyawan'],
        ['group' => 'User Management', 'slug' => 'user-management.users.index', 'name' => 'Lihat Daftar User'],
        ['group' => 'User Management', 'slug' => 'user-management.users.show', 'name' => 'Lihat Detail User'],
        ['group' => 'User Management', 'slug' => 'user-management.users.create', 'name' => 'Tambah User'],
        ['group' => 'User Management', 'slug' => 'user-management.users.edit', 'name' => 'Ubah User'],
        ['group' => 'User Management', 'slug' => 'user-management.users.delete', 'name' => 'Hapus User'],
        ['group' => 'User Management', 'slug' => 'user-management.roles.index', 'name' => 'Lihat Daftar Role'],
        ['group' => 'User Management', 'slug' => 'user-management.roles.create', 'name' => 'Tambah Role'],
        ['group' => 'User Management', 'slug' => 'user-management.roles.edit', 'name' => 'Ubah & Kelola Permission Role'],
        ['group' => 'User Management', 'slug' => 'user-management.roles.delete', 'name' => 'Hapus Role'],
        ['group' => 'User Management', 'slug' => 'user-management.permissions.index', 'name' => 'Lihat Daftar Permission'],
        ['group' => 'User Management', 'slug' => 'user-management.permissions.create', 'name' => 'Tambah Permission'],
        ['group' => 'User Management', 'slug' => 'user-management.permissions.edit', 'name' => 'Ubah Permission'],
        ['group' => 'User Management', 'slug' => 'user-management.permissions.delete', 'name' => 'Hapus Permission'],
        // Master Data > Administrasi (Barang)
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.goods-types.index', 'name' => 'Lihat Daftar Jenis Barang'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.goods-types.create', 'name' => 'Tambah Jenis Barang'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.goods-types.edit', 'name' => 'Ubah Jenis Barang'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.goods-types.delete', 'name' => 'Hapus Jenis Barang'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.goods-categories.index', 'name' => 'Lihat Daftar Kategori Barang'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.goods-categories.create', 'name' => 'Tambah Kategori Barang'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.goods-categories.edit', 'name' => 'Ubah Kategori Barang'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.goods-categories.delete', 'name' => 'Hapus Kategori Barang'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.packaging-types.index', 'name' => 'Lihat Daftar Jenis Kemasan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.packaging-types.create', 'name' => 'Tambah Jenis Kemasan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.packaging-types.edit', 'name' => 'Ubah Jenis Kemasan'],
        ['group' => 'Master Data > Administrasi', 'slug' => 'master-data.administration.packaging-types.delete', 'name' => 'Hapus Jenis Kemasan'],
        // Data Management > Pesanan
        ['group' => 'Data Management > Pesanan', 'slug' => 'data-management.orders.index', 'name' => 'Lihat Daftar Pesanan'],
        ['group' => 'Data Management > Pesanan', 'slug' => 'data-management.orders.show', 'name' => 'Lihat Detail Pesanan'],
        ['group' => 'Data Management > Pesanan', 'slug' => 'data-management.orders.create', 'name' => 'Tambah Pesanan'],
        ['group' => 'Data Management > Pesanan', 'slug' => 'data-management.orders.edit', 'name' => 'Ubah Pesanan'],
        ['group' => 'Data Management > Pesanan', 'slug' => 'data-management.orders.delete', 'name' => 'Hapus Pesanan'],
        ['group' => 'Data Management > Pesanan', 'slug' => 'data-management.orders.approval', 'name' => 'Setujui / Tolak Pesanan'],
    ];

    public function run(): void
    {
        $created = 0;
        $updated = 0;

        foreach ($this->permissions as $perm) {
            $record = Permission::firstOrCreate(
                ['slug' => $perm['slug']],
                ['name' => $perm['name'], 'group' => $perm['group']]
            );
            if ($record->wasRecentlyCreated) {
                $created++;
            } elseif ($record->name !== $perm['name'] || $record->group !== $perm['group']) {
                $record->update(['name' => $perm['name'], 'group' => $perm['group']]);
                $updated++;
            }
        }

        $total = count($this->permissions);
        $this->command->info("Permissions: {$total} diproses ({$created} baru, {$updated} diperbarui).");

        // Warn permission lama yang tidak ada di seeder ini
        $validSlugs = array_column($this->permissions, 'slug');
        $obsolete   = Permission::whereNotIn('slug', $validSlugs)->get();
        if ($obsolete->isNotEmpty()) {
            $this->command->warn("PERHATIAN: {$obsolete->count()} permission lama (tidak dihapus otomatis):");
            foreach ($obsolete as $old) {
                $this->command->warn("  - {$old->slug}");
            }
        }

        // Roles
        $allIds  = Permission::whereIn('slug', $validSlugs)->pluck('id')->toArray();
        $readIds = Permission::whereIn('slug', $validSlugs)
            ->where(function ($q) {
                $q->where('slug', 'like', '%.index')
                  ->orWhere('slug', 'like', '%.show');
            })->pluck('id')->toArray();

        $superadmin = Role::firstOrCreate(
            ['slug' => 'superadmin'],
            ['name' => 'Super Admin', 'description' => 'Akses penuh ke seluruh sistem']
        );
        $superadmin->permissions()->sync($allIds);

        $admin = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Akses penuh ke semua data']
        );
        $admin->permissions()->sync($allIds);

        $operator = Role::firstOrCreate(
            ['slug' => 'operator'],
            ['name' => 'Operator', 'description' => 'Hanya dapat melihat data (index & show)']
        );
        $operator->permissions()->sync($readIds);

        $this->command->info("Roles disync: superadmin & admin = semua | operator = index+show saja.");

        $user = User::find(1);
        if ($user) {
            $user->roles()->syncWithoutDetaching([$superadmin->id]);
            $this->command->info("User id=1 [{$user->full_name}] di-assign ke Superadmin.");
        }

        // Ringkasan
        $this->command->info('');
        $groups = [];
        foreach ($this->permissions as $p) {
            $groups[$p['group']] = ($groups[$p['group']] ?? 0) + 1;
        }
        foreach ($groups as $grp => $cnt) {
            $this->command->line("  {$grp}: {$cnt} permission");
        }
    }
}
