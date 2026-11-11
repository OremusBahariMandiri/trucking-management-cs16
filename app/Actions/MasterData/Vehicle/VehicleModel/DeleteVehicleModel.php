<?php

namespace App\Actions\MasterData\Vehicle\VehicleModel;

use App\Models\MasterData\Vehicle\VehicleModel;

class DeleteVehicleModel
{
    public function execute(VehicleModel $vehicleModel): void
    {
        $vehicleModel->delete();
    }
}
