<?php

namespace App\Models\MasterData\Vehicle;

use App\Models\DataManagement\FleetManagement\Fleet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BodyType extends Model
{
    protected $table = 'aa05_body_types';
    
    protected $fillable = ['code', 'name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Fleet::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}