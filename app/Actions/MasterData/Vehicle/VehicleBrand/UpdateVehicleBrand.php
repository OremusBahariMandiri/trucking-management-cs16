<?php

namespace App\Actions\MasterData\Vehicle\VehicleBrand;

use App\Models\MasterData\Vehicle\VehicleBrand;

class UpdateVehicleBrand
{
    public function execute(VehicleBrand $vehicleBrand, array $data): VehicleBrand
    {
        $vehicleBrand->update($data);

        return $vehicleBrand->fresh();
    }
}
