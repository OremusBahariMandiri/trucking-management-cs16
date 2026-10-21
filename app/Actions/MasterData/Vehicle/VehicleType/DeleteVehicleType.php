<?php

namespace App\Actions\MasterData\Vehicle\VehicleType;

use App\Models\MasterData\Vehicle\VehicleType;

class DeleteVehicleType
{
    public function execute(VehicleType $vehicleType): void
    {
        $vehicleType->delete();
    }
}
