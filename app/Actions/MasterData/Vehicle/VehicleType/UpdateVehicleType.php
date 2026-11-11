<?php

namespace App\Actions\MasterData\Vehicle\VehicleType;

use App\Models\MasterData\Vehicle\VehicleType;

class UpdateVehicleType
{
    public function execute(VehicleType $vehicleType, array $data): VehicleType
    {
        $vehicleType->update($data);

        return $vehicleType->fresh();
    }
}
