<?php

namespace App\Actions\MasterData\Administration\CustomerType;

use App\Models\MasterData\Administration\CustomerType;

class DeleteCustomerType
{
    public function execute(CustomerType $customerType): void
    {
        $customerType->delete();
    }
}
