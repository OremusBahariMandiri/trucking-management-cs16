<?php
namespace App\Models\DataManagement\OrderManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\MasterData\Administration\Customer;

class Order extends Model
{

    protected $table = 'bd01_01_orders';

    protected $fillable = [
        'po_number','po_date','customer_id','customer_name','customer_address',
        'pic_name','pic_phone',
        'total_amount','payment_method','payment_due_date',
        'special_notes',
        'submission_date','approval_status','approval_notes','approval_date','approved_by',
        'created_by','updated_by',
    ];

    protected $casts = [
        'po_date'         => 'date',
        'payment_due_date'=> 'date',
        'submission_date' => 'date',
        'approval_date'   => 'date',
    ];

    public function customer()   { return $this->belongsTo(Customer::class); }
    public function approvedBy() { return $this->belongsTo(\App\Models\User::class, 'approved_by'); }
    public function createdBy()  { return $this->belongsTo(\App\Models\User::class, 'created_by'); }

    public function shipments(): HasMany
    {
        return $this->hasMany(OrderShipment::class)->orderBy('sort_order');
    }

    public function costs(): HasMany
    {
        return $this->hasMany(OrderCost::class)->orderBy('sort_order');
    }

    public function getApprovalStatusLabelAttribute(): string
    {
        return match($this->approval_status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => 'Menunggu',
        };
    }

    public function getApprovalStatusEnLabelAttribute(): string
    {
        return match($this->approval_status) {
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default    => 'Pending',
        };
    }

    /** Grand total dari semua cost lines */
    public function getTotalCostAttribute(): float
    {
        return $this->costs->sum('total_amount');
    }
}
