<?php

namespace App\Actions\MasterData\Vehicle\VehicleType;

use App\Models\MasterData\Vehicle\VehicleType;

class CreateVehicleType
{
    public function execute(array $data): VehicleType
    {
        return VehicleType::create($data);
    }
}
