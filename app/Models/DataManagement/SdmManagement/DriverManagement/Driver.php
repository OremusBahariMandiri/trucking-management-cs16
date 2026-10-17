<?php

// app/Models/SdmManagement/DriverManagement/Driver.php

namespace App\Models\DataManagement\SdmManagement\DriverManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $table = 'bb01_drivers';

    protected $fillable = [
        'driver_code',
        'full_name',
        'gender',
        'phone',
        'email',
        'birth_date',
        'birth_place',
        'marital_status',
        'children_count',
        'status',
        'join_date',
        'photo_path',
        'nik',
        'ktp_file_path',
        'ktp_address',
        'ktp_village',
        'ktp_district',
        'ktp_city',
        'ktp_province',
        'ktp_postal_code',
        'domicile_same_as_ktp',
        'domicile_address',
        'domicile_village',
        'domicile_district',
        'domicile_city',
        'domicile_province',
        'domicile_postal_code',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'birth_date'            => 'date',
        'join_date'             => 'date',
        'domicile_same_as_ktp'  => 'boolean',
        'children_count'        => 'integer',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function documents(): HasMany
    {
        return $this->hasMany(DriverDocument::class, 'driver_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(DriverCertificate::class, 'driver_id');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('full_name', 'like', "%{$keyword}%")
              ->orWhere('driver_code', 'like', "%{$keyword}%")
              ->orWhere('nik', 'like', "%{$keyword}%")
              ->orWhere('phone', 'like', "%{$keyword}%");
        });
    }

    // ── Accessors ──────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active'   => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'resigned' => 'Keluar',
            default    => '-',
        };
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'male' ? 'Laki-laki' : 'Perempuan';
    }
}