<?php

namespace App\Actions\MasterData\Vehicle\VehicleBrand;

use App\Models\MasterData\Vehicle\VehicleBrand;

class DeleteVehicleBrand
{
    public function execute(VehicleBrand $vehicleBrand): void
    {
        $vehicleBrand->delete();
    }
}
