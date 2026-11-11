<?php

namespace App\Models\MasterData\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonInCharge extends Model
{
    protected $table = 'ad04_person_in_charges';

    protected $fillable = [
        'customer_id', 'name', 'position', 'department',
        'phone', 'email', 'is_primary',
    ];

    protected $casts = ['is_primary' => 'boolean'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
