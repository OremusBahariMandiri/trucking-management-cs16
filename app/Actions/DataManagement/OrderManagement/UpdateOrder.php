<?php
namespace App\Actions\DataManagement\OrderManagement;
use App\Models\DataManagement\OrderManagement\Order;

class UpdateOrder
{
    public function execute(Order $order, array $data): Order
    {
        $data['updated_by'] = auth()->id();
        $order->update($data);
        return $order;
    }
}
