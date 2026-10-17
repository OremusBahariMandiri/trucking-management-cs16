<?php

// app/Models/SdmManagement/DriverManagement/DriverCertificate.php

namespace App\Models\DataManagement\SdmManagement\DriverManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverCertificate extends Model
{
    protected $table = 'bb03_driver_certificates';

    protected $fillable = [
        'driver_id',
        'certificate_name',
        'certificate_number',
        'issuer',
        'issued_date',
        'expiry_date',
        'description',
        'file_path',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expiry_date
            && !$this->expiry_date->isPast()
            && $this->expiry_date->diffInDays(now()) <= 30;
    }
}