<?php

namespace App\Actions\MasterData\Vehicle\BodyType;

use App\Models\MasterData\Vehicle\BodyType;

class CreateBodyType
{
    public function execute(array $data): BodyType
    {
        return BodyType::create($data);
    }
}
