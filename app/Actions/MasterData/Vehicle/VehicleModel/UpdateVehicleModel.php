<?php

namespace App\Actions\MasterData\Vehicle\VehicleModel;

use App\Models\MasterData\Vehicle\VehicleModel;

class UpdateVehicleModel
{
    public function execute(VehicleModel $vehicleModel, array $data): VehicleModel
    {
        $vehicleModel->update($data);

        return $vehicleModel->fresh();
    }
}
