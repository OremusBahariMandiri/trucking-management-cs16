<?php

// app/Models/SdmManagement/DriverManagement/DriverDocument.php

namespace App\Models\DataManagement\SdmManagement\DriverManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverDocument extends Model
{
    protected $table = 'bb02_driver_documents';

    protected $fillable = [
        'driver_id',
        'document_name',
        'document_number',
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