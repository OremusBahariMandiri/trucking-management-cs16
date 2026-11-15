<?php

namespace App\Actions\MasterData\Vehicle\BodyType;

use App\Models\MasterData\Vehicle\BodyType;

class DeleteBodyType
{
    public function execute(BodyType $bodyType): void
    {
        $bodyType->delete();
    }
}
