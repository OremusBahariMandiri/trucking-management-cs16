<?php

namespace App\Models\DataManagement\SdmManagement\DriverManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverEmergencyContact extends Model
{
    protected $table = 'bb04_driver_emergency_contacts';

    protected $fillable = [
        'driver_id',
        'contact_type',
        'contact_status',
        'full_name',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'marital_status',
        'phone_primary',
        'phone_alternative',
        'province',
        'city',
        'district',
        'village',
        'rt_rw',
        'postal_code',
        'full_address',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    // ── Accessors ──────────────────────────────────────────────────────────

    public function getContactTypeLabelAttribute(): string
    {
        return match($this->contact_type) {
            'orang_tua'    => 'Orang Tua',
            'suami'       => 'Suami',
            'istri' => 'Istri',
            'saudara_kandung'     => 'Saudara Kandung',
            default       => '-',
        };
    }

    public function getContactStatusLabelAttribute(): string
    {
        return $this->contact_status === 'aktif' ? 'Aktif' : 'Tidak Aktif';
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'male' ? 'Laki-laki' : 'Perempuan';
    }

    public function getReligionLabelAttribute(): string
    {
        return match($this->religion) {
            'islam'     => 'Islam',
            'kristen'   => 'Kristen',
            'katolik'   => 'Katolik',
            'hindu'     => 'Hindu',
            'budha'     => 'Budha',
            'konghucu'  => 'Konghucu',
            default     => '-',
        };
    }

    public function getMaritalStatusLabelAttribute(): string
    {
        return match($this->marital_status) {
            'single'   => 'Belum Menikah',
            'married'  => 'Menikah',
            'divorced' => 'Cerai',
            'widowed'  => 'Duda/Janda',
            default    => '-',
        };
    }
}
