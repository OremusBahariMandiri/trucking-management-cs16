<?php

namespace App\Actions\MasterData\Vehicle\VehicleAxisConfiguration;

use App\Models\MasterData\Vehicle\VehicleAxisConfiguration;

class UpdateVehicleAxisConfiguration
{
    public function execute(VehicleAxisConfiguration $axisConfiguration, array $data): VehicleAxisConfiguration
    {
        $axisConfiguration->update($data);

        return $axisConfiguration->fresh();
    }
}
