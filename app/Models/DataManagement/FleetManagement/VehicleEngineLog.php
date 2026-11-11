<?php

namespace App\Models\DataManagement\FleetManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleEngineLog extends Model
{
    protected $table = 'aa06_vehicle_engine_logs';

    protected $fillable = [
        'vehicle_id',
        'gps_tracker_id',
        'event',
        'lat',
        'lng',
        'speed',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'lat'         => 'decimal:7',
        'lng'         => 'decimal:7',
        'speed'       => 'integer',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Fleet::class, 'vehicle_id');
    }
}
