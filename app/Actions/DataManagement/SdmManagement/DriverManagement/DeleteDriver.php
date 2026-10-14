<?php

namespace App\Actions\DataManagement\SdmManagement\DriverManagement;

use App\Models\DataManagement\SdmManagement\DriverManagement\Driver;

class DeleteDriver
{
    public function handle(Driver $driver): void
    {
        $driver->delete();
    }
}