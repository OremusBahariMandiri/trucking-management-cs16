<?php
namespace App\Http\Requests\DataManagement;
use Illuminate\Foundation\Http\FormRequest;
class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $orderId = $this->route('order')?->id ?? $this->route('order');
        return [
            'po_number'          => "required|string|max:50|unique:bd01_01_orders,po_number,{$orderId}",
            'po_date'            => 'required|date',
            'customer_id'        => 'nullable|exists:ad03_customers,id',
            'customer_name'      => 'nullable|string|max:150',
            'customer_address'   => 'nullable|string',
            'pic_name'           => 'nullable|string|max:150',
            'pic_phone'          => 'nullable|string|max:30',
            'goods_type_id'      => 'nullable|exists:ad05_goods_types,id',
            'goods_category_id'  => 'nullable|exists:ad06_goods_categories,id',
            'packaging_type_id'  => 'nullable|exists:ad07_packaging_types,id',
            'loading_address'    => 'nullable|string',
            'unloading_address'  => 'nullable|string',
            'loading_date'       => 'nullable|date',
            'unloading_date'     => 'nullable|date',
            'vehicle_type'       => 'nullable|string|max:100',
            'vehicle_capacity'   => 'nullable|numeric',
            'vehicle_quantity'   => 'nullable|integer',
            'total_volume'       => 'nullable|numeric',
            'measurement_unit'   => 'nullable|string|max:30',
            'cost_description'   => 'nullable|string|max:200',
            'cost_qty'           => 'nullable|numeric',
            'cost_unit_price'    => 'nullable|numeric',
            'ppn_percent'        => 'nullable|numeric|min:0|max:100',
            'payment_method'     => 'nullable|string|max:100',
            'payment_due_date'   => 'nullable|date',
            'special_notes'      => 'nullable|string',
            'submission_date'    => 'nullable|date',
            'approval_status'    => 'nullable|in:pending,approved,rejected',
            'approval_notes'     => 'nullable|string',
            'approval_date'      => 'nullable|date',
        ];
    }
}
