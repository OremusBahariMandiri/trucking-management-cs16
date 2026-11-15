<?php

namespace App\Actions\MasterData\Vehicle\OwnershipStatus;

use App\Models\MasterData\Vehicle\OwnershipStatus;

class UpdateOwnershipStatus
{
    public function execute(OwnershipStatus $ownershipStatus, array $data): OwnershipStatus
    {
        $ownershipStatus->update($data);

        return $ownershipStatus->fresh();
    }
}
