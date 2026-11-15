<?php

namespace App\Actions\MasterData\Administration\CustomerType;

use App\Models\MasterData\Administration\CustomerType;

class CreateCustomerType
{
    public function execute(array $data): CustomerType
    {
        return CustomerType::create($data);
    }
}
