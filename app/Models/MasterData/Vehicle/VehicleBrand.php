<?php

namespace App\Models\MasterData\Vehicle;

use App\Models\DataManagement\FleetManagement\Fleet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleBrand extends Model
{
    protected $table = 'aa02_vehicle_brands';

    protected $fillable = ['code', 'name', 'country_of_origin', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Fleet::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
