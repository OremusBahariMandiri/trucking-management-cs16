<?php

namespace App\Models\MasterData\Vehicle;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehiclePhoto extends Model
{
    protected $fillable = [
        'vehicle_id', 'category', 'caption',
        'file_path', 'file_name', 'file_size_kb',
        'sort_order', 'is_primary', 'uploaded_by',
    ];

    protected $casts = [
        'is_primary'   => 'boolean',
        'sort_order'   => 'integer',
        'file_size_kb' => 'integer',
    ];

    const CATEGORIES = [
        'tampak_depan'         => 'Tampak Depan',
        'tampak_belakang'      => 'Tampak Belakang',
        'tampak_samping_kiri'  => 'Tampak Samping Kiri',
        'tampak_samping_kanan' => 'Tampak Samping Kanan',
        'interior_kabin'       => 'Interior Kabin',
        'karoseri'             => 'Karoseri',
        'mesin'                => 'Mesin',
        'plat_nomor'           => 'Plat Nomor',
        'nomor_rangka'         => 'Nomor Rangka',
        'nomor_mesin'          => 'Nomor Mesin',
        'kerusakan'            => 'Kerusakan',
        'dokumen'              => 'Dokumen',
        'lainnya'              => 'Lainnya',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}