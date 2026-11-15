<?php

namespace App\Models\DataManagement\FleetManagement;

use App\Models\MasterData\Vehicle\BodyType;
use App\Models\MasterData\Vehicle\FuelType;
use App\Models\MasterData\Vehicle\OwnershipStatus;
use App\Models\MasterData\Vehicle\VehicleAxisConfiguration;
use App\Models\MasterData\Vehicle\VehicleBrand;
use App\Models\MasterData\Vehicle\VehicleDocument;
use App\Models\MasterData\Vehicle\VehicleModel;
use App\Models\MasterData\Vehicle\VehiclePhoto;
use App\Models\MasterData\Vehicle\VehicleType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fleet extends Model
{
    protected $table = 'aa06_vehicles';

    protected $fillable = [
        // Tab 1: Informasi Umum
        'vehicle_code',
        'police_number',
        'hull_number',
        'vehicle_type_id',
        'vehicle_brand_id',
        'vehicle_model_id',
        'model_name',
        'manufacture_year',
        'color',
        'vehicle_status',

        // Tab 2: Spesifikasi Teknis
        'chassis_number',
        'engine_number',
        'fuel_type_id',
        'engine_capacity_cc',
        'axis_configuration',
        'wheel_count',
        'load_capacity_ton',
        'gross_vehicle_weight_ton',
        'tare_weight_ton',
        'body_type_id',
        'body_dimension',

        // Tab 3: Kepemilikan & Penugasan
        'ownership_status',
        'acquisition_year',
        'acquisition_value',
        'leasing_company',
        'leasing_end_date',
        'department_id',
        'branch_id',
        'driver_id',

        // Tab 4: Operasional
        'initial_odometer_km',
        'initial_odometer_date',
        'last_odometer_km',
        'last_odometer_date',
        'last_service_date',
        'service_interval_km',
        'gps_tracker_id',
        'gps_provider',
        'gps_status',
        'gps_last_lat',
        'gps_last_lng',
        'gps_last_speed',
        'gps_last_acc',
        'gps_last_seen',
        'gps_last_address',

        // Tab 5: Dokumen & Legalitas
        'stnk_number',
        'tax_expiry_date',
        'kir_number',
        'kir_expiry_date',
        'bpkb_number',
        'insurance_policy_number',
        'insurance_company',
        'insurance_expiry_date',

        // Tab 7: Catatan
        'notes',

        // Audit
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'manufacture_year'          => 'integer',
        'engine_capacity_cc'        => 'integer',
        'axis_configuration'        => 'integer',   // ← tambah
        'wheel_count'               => 'integer',
        'load_capacity_ton'         => 'decimal:2',
        'gross_vehicle_weight_ton'  => 'decimal:2',
        'tare_weight_ton'           => 'decimal:2',
        'ownership_status'          => 'integer',   // ← tambah
        'acquisition_year'          => 'integer',
        'acquisition_value'         => 'decimal:2',
        'leasing_end_date'          => 'date',
        'initial_odometer_km'       => 'integer',
        'initial_odometer_date'     => 'date',
        'last_odometer_km'          => 'integer',
        'last_odometer_date'        => 'date',
        'last_service_date'         => 'date',
        'service_interval_km'       => 'integer',
        'tax_expiry_date'           => 'date',
        'kir_expiry_date'           => 'date',
        'insurance_expiry_date'     => 'date',
        'gps_last_lat'              => 'decimal:7',
        'gps_last_lng'              => 'decimal:7',
        'gps_last_speed'            => 'integer',
        'gps_last_acc'              => 'integer',
        'gps_last_seen'             => 'datetime',
    ];

    // ── Relasi ke lookup ──────────────────────────────────────────────────────

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brand_id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id');
    }

    public function bodyType(): BelongsTo
    {
        return $this->belongsTo(BodyType::class, 'body_type_id');
    }

    public function axisConfiguration(): BelongsTo  // ← tambah
    {
        return $this->belongsTo(VehicleAxisConfiguration::class, 'axis_configuration');
    }

    public function ownershipStatus(): BelongsTo    // ← tambah
    {
        return $this->belongsTo(OwnershipStatus::class, 'ownership_status');
    }

    // ── Relasi ke user ────────────────────────────────────────────────────────

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ── Relasi ke foto & dokumen ──────────────────────────────────────────────

    public function photos(): HasMany
    {
        return $this->hasMany(VehiclePhoto::class, 'vehicle_id')->orderBy('sort_order');
    }

    public function primaryPhoto(): HasMany
    {
        return $this->hasMany(VehiclePhoto::class, 'vehicle_id')->where('is_primary', true);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VehicleDocument::class, 'vehicle_id');
    }

    // ── Accessor ──────────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->vehicle_status) {
            'active'   => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'service'  => 'Servis',
            'broken'   => 'Rusak',
            'sold'     => 'Terjual',
            default    => ucfirst($this->vehicle_status ?? '-'),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->vehicle_status) {
            'active'   => 'green',
            'inactive' => 'blue',
            'service'  => 'orange',
            'broken'   => 'red',
            'sold'     => 'purple',
            default    => 'blue',
        };
    }

    public function getOwnershipLabelAttribute(): string  // ← fix: pakai relasi
    {
        return $this->ownershipStatus?->name ?? '-';
    }

    // ── Scope ─────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('vehicle_status', 'active');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('vehicle_status', $status);
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('vehicle_code', 'like', "%{$keyword}%")
                ->orWhere('police_number', 'like', "%{$keyword}%")
                ->orWhere('hull_number', 'like', "%{$keyword}%")
                ->orWhere('chassis_number', 'like', "%{$keyword}%");
        });
    }
}