<?php
namespace App\Actions\DataManagement\OrderManagement;
use App\Models\DataManagement\OrderManagement\Order;

class CreateOrder
{
    public function execute(array $data): Order
    {
        $data['created_by'] = auth()->id();
        return Order::create($data);
    }
}
