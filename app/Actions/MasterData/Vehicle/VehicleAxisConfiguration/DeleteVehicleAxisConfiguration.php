<?php

namespace App\Actions\MasterData\Vehicle\VehicleAxisConfiguration;

use App\Models\MasterData\Vehicle\VehicleAxisConfiguration;

class DeleteVehicleAxisConfiguration
{
    public function execute(VehicleAxisConfiguration $axisConfiguration): void
    {
        $axisConfiguration->delete();
    }
}
