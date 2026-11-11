<?php

namespace App\Models\MasterData\Administration;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    protected $table = 'ad03_customers';

    protected $fillable = [
        'registration_date', 'customer_code', 'name',
        'customer_type_id', 'business_type_id',
        'npwp_number', 'npwp_file_path', 'npwp_file_name',
        'nib_number',  'nib_file_path',  'nib_file_name',
        'address', 'rt_rw', 'kelurahan', 'kecamatan',
        'city', 'province', 'postal_code',
        'phone', 'email', 'is_active',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'is_active'         => 'boolean',
        'registration_date' => 'date',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function customerType(): BelongsTo
    {
        return $this->belongsTo(CustomerType::class);
    }

    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class);
    }

    public function pics(): HasMany
    {
        return $this->hasMany(PersonInCharge::class);
    }

    public function primaryPic(): HasOne
    {
        return $this->hasOne(PersonInCharge::class)->where('is_primary', true);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('customer_code', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    // ── Accessors ──────────────────────────────────────────────────────────

    public function getNpwpFileUrlAttribute(): ?string
    {
        return $this->npwp_file_path ? asset('storage/' . $this->npwp_file_path) : null;
    }

    public function getNibFileUrlAttribute(): ?string
    {
        return $this->nib_file_path ? asset('storage/' . $this->nib_file_path) : null;
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address,
            $this->rt_rw ? 'RT/RW ' . $this->rt_rw : null,
            $this->kelurahan,
            $this->kecamatan,
            $this->city,
            $this->province,
            $this->postal_code,
        ])->filter()->implode(', ');
    }
}
