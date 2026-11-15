<?php

namespace App\Actions\MasterData\Administration\BusinessType;

use App\Models\MasterData\Administration\BusinessType;

class CreateBusinessType
{
    public function execute(array $data): BusinessType
    {
        return BusinessType::create($data);
    }
}
