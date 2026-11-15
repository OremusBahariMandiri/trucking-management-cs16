<?php

namespace App\Actions\MasterData\Vehicle\OwnershipStatus;

use App\Models\MasterData\Vehicle\OwnershipStatus;

class DeleteOwnershipStatus
{
    public function execute(OwnershipStatus $ownershipStatus): void
    {
        $ownershipStatus->delete();
    }
}
