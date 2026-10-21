<?php

namespace App\Actions\MasterData\Vehicle\FuelType;

use App\Models\MasterData\Vehicle\FuelType;

class DeleteFuelType
{
    public function execute(FuelType $fuelType): void
    {
        $fuelType->delete();
    }
}
