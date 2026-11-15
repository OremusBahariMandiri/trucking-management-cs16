<?php

namespace App\Actions\MasterData\Administration\CustomerType;

use App\Models\MasterData\Administration\CustomerType;

class UpdateCustomerType
{
    public function execute(CustomerType $customerType, array $data): CustomerType
    {
        $customerType->update($data);

        return $customerType->fresh();
    }
}
