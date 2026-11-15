<?php

namespace App\Models\DataManagement\OrderManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderCost extends Model
{
    protected $table = 'bd01_03_order_costs';

    protected $fillable = [
        'order_id',
        'cost_description',
        'cost_qty',
        'cost_unit_price',
        'cost_subtotal',
        'ppn_percent',
        'ppn_amount',
        'total_amount',
        'sort_order',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
