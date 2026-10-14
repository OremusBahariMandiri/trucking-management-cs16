<?php

namespace App\Models\MasterData\Vehicle;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleDocument extends Model
{
    protected $table = 'aa08_vehicle_documents';
    
    protected $fillable = [
        'vehicle_id', 'document_type', 'document_number',
        'issued_by', 'issue_date', 'expiry_date',
        'file_path', 'file_name', 'file_size_kb',
        'notes', 'uploaded_by',
    ];

    protected $casts = [
        'issue_date'   => 'date',
        'expiry_date'  => 'date',
        'file_size_kb' => 'integer',
    ];

    const TYPES = [
        'stnk'     => 'STNK',
        'kir'      => 'KIR',
        'bpkb'     => 'BPKB',
        'asuransi' => 'Asuransi',
        'faktur'   => 'Faktur',
        'sio'      => 'SIO',
        'lainnya'  => 'Lainnya',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->document_type] ?? ucfirst($this->document_type);
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

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->whereNotNull('expiry_date')
                     ->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
                     ->where('expiry_date', '<', now());
    }
}