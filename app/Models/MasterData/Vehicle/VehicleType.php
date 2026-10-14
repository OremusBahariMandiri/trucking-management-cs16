<?php

namespace App\Models\MasterData\Vehicle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleType extends Model
{
    protected $fillable = ['code', 'name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}