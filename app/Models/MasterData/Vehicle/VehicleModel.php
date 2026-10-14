<?php

namespace App\Models\MasterData\Vehicle;

use App\Models\DataManagement\FleetManagement\Fleet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleModel extends Model
{
    protected $table = 'aa03_vehicle_models';

    protected $fillable = [
        'vehicle_brand_id',
        'vehicle_type_id',
        'name',
        'year_start',
        'year_end',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'year_start' => 'integer',
        'year_end'   => 'integer',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brand_id');
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Fleet::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByBrand($query, int $brandId)
    {
        return $query->where('vehicle_brand_id', $brandId);
    }
}
