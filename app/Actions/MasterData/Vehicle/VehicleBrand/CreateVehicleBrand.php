<?php

namespace App\Actions\MasterData\Vehicle\VehicleBrand;

use App\Models\MasterData\Vehicle\VehicleBrand;

class CreateVehicleBrand
{
    public function execute(array $data): VehicleBrand
    {
        return VehicleBrand::create($data);
    }
}
