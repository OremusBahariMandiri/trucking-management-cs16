<?php

namespace App\Actions\MasterData\Vehicle\VehicleAxisConfiguration;

use App\Models\MasterData\Vehicle\VehicleAxisConfiguration;

class CreateVehicleAxisConfiguration
{
    public function execute(array $data): VehicleAxisConfiguration
    {
        return VehicleAxisConfiguration::create($data);
    }
}
