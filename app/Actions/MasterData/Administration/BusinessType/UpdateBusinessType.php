<?php

namespace App\Actions\MasterData\Administration\BusinessType;

use App\Models\MasterData\Administration\BusinessType;

class UpdateBusinessType
{
    public function execute(BusinessType $businessType, array $data): BusinessType
    {
        $businessType->update($data);

        return $businessType->fresh();
    }
}
