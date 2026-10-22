<?php

namespace App\Actions\MasterData\Vehicle\FuelType;

use App\Models\MasterData\Vehicle\FuelType;

class UpdateFuelType
{
    public function execute(FuelType $fuelType, array $data): FuelType
    {
        $fuelType->update($data);

        return $fuelType->fresh();
    }
}
