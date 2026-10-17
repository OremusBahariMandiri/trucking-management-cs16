<?php

namespace App\Actions\DataManagement\SdmManagement\EmployeeManagement;

use App\Models\DataManagement\SdmManagement\EmployeeManagement\Employee;

class DeleteEmployee
{
    public function handle(Employee $employee): void
    {
        $employee->delete();
    }
}