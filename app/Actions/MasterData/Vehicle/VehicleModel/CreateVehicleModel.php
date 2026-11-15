<?php

namespace App\Actions\MasterData\Vehicle\VehicleModel;

use App\Models\MasterData\Vehicle\VehicleModel;

class CreateVehicleModel
{
    public function execute(array $data): VehicleModel
    {
        return VehicleModel::create($data);
    }
}
