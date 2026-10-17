<?php

namespace App\Actions\DataManagement\SdmManagement\EmployeeManagement;

use App\Models\DataManagement\SdmManagement\EmployeeManagement\Employee;
use Illuminate\Support\Facades\DB;

class UpdateEmployee
{
    public function handle(Employee $employee, array $data): Employee
    {
        return DB::transaction(function () use ($employee, $data) {
            $employee->update($data);

            return $employee->fresh();
        });
    }
}