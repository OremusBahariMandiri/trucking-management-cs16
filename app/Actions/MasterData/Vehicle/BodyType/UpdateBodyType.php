<?php

namespace App\Actions\MasterData\Vehicle\BodyType;

use App\Models\MasterData\Vehicle\BodyType;

class UpdateBodyType
{
    public function execute(BodyType $bodyType, array $data): BodyType
    {
        $bodyType->update($data);

        return $bodyType->fresh();
    }
}
