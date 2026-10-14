<?php

namespace App\Services\MasterData\Vehicle;

use App\Models\MasterData\Vehicle\BodyType;
use App\Models\MasterData\Vehicle\FuelType;
use App\Models\MasterData\Vehicle\Vehicle;
use App\Models\MasterData\Vehicle\VehicleBrand;
use App\Models\MasterData\Vehicle\VehicleModel;
use App\Models\MasterData\Vehicle\VehicleType;
use Illuminate\Pagination\LengthAwarePaginator;

class VehicleService
{
    /**
     * Ambil daftar kendaraan dengan filter & pagination.
     */
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Vehicle::query()
            ->with(['type', 'brand', 'model', 'photos' => fn($q) => $q->where('is_primary', true)])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->search($search);
            })
            ->when($filters['status'] ?? null, function ($q, $status) {
                $q->byStatus($status);
            })
            ->when($filters['type_id'] ?? null, function ($q, $typeId) {
                $q->where('vehicle_type_id', $typeId);
            })
            ->when($filters['brand_id'] ?? null, function ($q, $brandId) {
                $q->where('vehicle_brand_id', $brandId);
            })
            ->when($filters['ownership'] ?? null, function ($q, $ownership) {
                $q->where('ownership_status', $ownership);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Ambil satu kendaraan lengkap dengan semua relasi.
     */
    public function findWithRelations(int $id): Vehicle
    {
        return Vehicle::with([
            'type', 'brand', 'model', 'fuelType', 'bodyType',
            'driver', 'photos', 'documents',
            'createdBy', 'updatedBy',
        ])->findOrFail($id);
    }

    /**
     * Kumpulkan semua data lookup untuk form (dropdown).
     */
    public function formOptions(): array
    {
        return [
            'vehicle_types'  => VehicleType::active()->orderBy('name')->get(),
            'vehicle_brands' => VehicleBrand::active()->orderBy('name')->get(),
            'vehicle_models' => VehicleModel::active()->with('brand')->orderBy('name')->get(),
            'fuel_types'     => FuelType::active()->orderBy('name')->get(),
            'body_types'     => BodyType::active()->orderBy('name')->get(),
            'axis_configs'   => ['4x2', '6x2', '6x4', '8x4', '10x4', 'other'],
            'statuses'       => [
                'active'   => 'Aktif',
                'inactive' => 'Tidak Aktif',
                'service'  => 'Servis',
                'broken'   => 'Rusak',
                'sold'     => 'Terjual',
            ],
            'ownership_statuses' => [
                'company'   => 'Milik Perusahaan',
                'rental'    => 'Sewa',
                'leasing'   => 'Leasing',
                'kerjasama' => 'Kerjasama',
            ],
            'gps_statuses' => [
                'active'        => 'Aktif',
                'offline'       => 'Offline',
                'not_installed' => 'Tidak Terpasang',
            ],
            'photo_categories' => \App\Models\MasterData\Vehicle\VehiclePhoto::CATEGORIES,
            'document_types'   => \App\Models\MasterData\Vehicle\VehicleDocument::TYPES,
        ];
    }

    /**
     * Generate kode kendaraan otomatis: TRK-2024-001
     */
    public function generateVehicleCode(): string
    {
        $year  = now()->year;
        $last  = Vehicle::whereYear('created_at', $year)->count();
        $seq   = str_pad($last + 1, 3, '0', STR_PAD_LEFT);

        return "TRK-{$year}-{$seq}";
    }

    /**
     * Statistik ringkas untuk dashboard.
     */
    public function stats(): array
    {
        return [
            'total'    => Vehicle::count(),
            'active'   => Vehicle::byStatus('active')->count(),
            'service'  => Vehicle::byStatus('service')->count(),
            'broken'   => Vehicle::byStatus('broken')->count(),
        ];
    }
}