<?php

namespace App\Actions\MasterData\Vehicle\FuelType;

use App\Models\MasterData\Vehicle\FuelType;

class CreateFuelType
{
    public function execute(array $data): FuelType
    {
        return FuelType::create($data);
    }
}
