<?php
namespace App\Actions\DataManagement\OrderManagement;
use App\Models\DataManagement\OrderManagement\Order;

class DeleteOrder
{
    public function execute(Order $order): void
    {
        $order->delete();
    }
}
