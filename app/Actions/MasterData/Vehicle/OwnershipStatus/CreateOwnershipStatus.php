<?php

namespace App\Actions\MasterData\Vehicle\OwnershipStatus;

use App\Models\MasterData\Vehicle\OwnershipStatus;

class CreateOwnershipStatus
{
    public function execute(array $data): OwnershipStatus
    {
        return OwnershipStatus::create($data);
    }
}
