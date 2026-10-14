<?php

namespace App\Models\MasterData\Vehicle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleBrand extends Model
{
    protected $fillable = ['name', 'country_of_origin', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}