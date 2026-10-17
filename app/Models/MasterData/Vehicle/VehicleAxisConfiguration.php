<?php

namespace App\Models\MasterData\Vehicle;

use App\Models\DataManagement\FleetManagement\Fleet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleAxisConfiguration extends Model
{
    protected $table = 'aa09_vehicle_axis_configurations';

    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function fleets(): HasMany
    {
        return $this->hasMany(Fleet::class, 'axis_configuration', 'id');
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
