<?php

namespace App\Models\MasterData\Vehicle;

use App\Models\DataManagement\FleetManagement\Fleet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OwnershipStatus extends Model
{
    protected $table = 'aa10_ownership_statuses';

    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function fleets(): HasMany
    {
        return $this->hasMany(Fleet::class, 'ownership_status', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
