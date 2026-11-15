<?php

namespace App\Actions\MasterData\Administration\BusinessType;

use App\Models\MasterData\Administration\BusinessType;

class DeleteBusinessType
{
    public function execute(BusinessType $businessType): void
    {
        $businessType->delete();
    }
}
