<?php

namespace App\Models\DataManagement\OrderManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\MasterData\Administration\GoodsType;
use App\Models\MasterData\Administration\GoodsCategory;
use App\Models\MasterData\Administration\PackagingType;
use App\Models\MasterData\Vehicle\VehicleType;

class OrderShipment extends Model
{
    protected $table = 'bd01_02_order_shipments';

    protected $fillable = [
        'order_id',
        'goods_type_id', 'goods_type_name',
        'goods_category_id', 'goods_category_name',
        'packaging_type_id', 'packaging_type_name',
        'loading_address', 'unloading_address',
        'loading_date', 'unloading_date',
        'vehicle_type_id', 'vehicle_type_name',
        'vehicle_capacity', 'vehicle_quantity',
        'total_volume', 'measurement_unit',
        'sort_order',
    ];

    protected $casts = [
        'loading_date'   => 'date',
        'unloading_date' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function goodsType(): BelongsTo
    {
        return $this->belongsTo(GoodsType::class);
    }

    public function goodsCategory(): BelongsTo
    {
        return $this->belongsTo(GoodsCategory::class);
    }

    public function packagingType(): BelongsTo
    {
        return $this->belongsTo(PackagingType::class);
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    // Accessor: ambil nama dari relasi, fallback ke kolom *_name (data lama)
    public function getGoodsTypeDisplayAttribute(): string
    {
        return $this->goodsType?->name ?? $this->goods_type_name ?? '—';
    }

    public function getGoodsCategoryDisplayAttribute(): string
    {
        return $this->goodsCategory?->name ?? $this->goods_category_name ?? '—';
    }

    public function getPackagingTypeDisplayAttribute(): string
    {
        return $this->packagingType?->name ?? $this->packaging_type_name ?? '—';
    }

    public function getVehicleTypeDisplayAttribute(): string
    {
        return $this->vehicleType?->name ?? $this->vehicle_type_name ?? '—';
    }
}
