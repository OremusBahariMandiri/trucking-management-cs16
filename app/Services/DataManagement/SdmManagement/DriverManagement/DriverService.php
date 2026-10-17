<?php

namespace App\Services\DataManagement\SdmManagement\DriverManagement;

use App\Models\DataManagement\SdmManagement\DriverManagement\Driver;
use Illuminate\Pagination\LengthAwarePaginator;

class DriverService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Driver::query()
            ->when($filters['search'] ?? null, fn($q, $v) => $q->search($v))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findWithRelations(int $id): Driver
    {
        return Driver::with(['documents', 'certificates'])->findOrFail($id);
    }

    public function formOptions(): array
    {
        return [
            'statuses' => [
                'active'   => 'Aktif',
                'inactive' => 'Tidak Aktif',
                'resigned' => 'Keluar',
            ],
            'genders' => [
                'male'   => 'Laki-laki',
                'female' => 'Perempuan',
            ],
            'marital_statuses' => [
                'single'   => 'Belum Menikah',
                'married'  => 'Menikah',
                'divorced' => 'Cerai',
                'widowed'  => 'Duda/Janda',
            ],
        ];
    }

    public function generateDriverCode(): string
    {
        $year  = now()->year;
        $count = Driver::whereYear('created_at', $year)->count();
        $seq   = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "DRV-{$year}-{$seq}";
    }

    public function stats(): array
    {
        return [
            'total'    => Driver::count(),
            'active'   => Driver::where('status', 'active')->count(),
            'inactive' => Driver::where('status', 'inactive')->count(),
            'resigned' => Driver::where('status', 'resigned')->count(),
        ];
    }
}