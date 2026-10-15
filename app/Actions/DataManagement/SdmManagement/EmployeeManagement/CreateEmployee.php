<?php

namespace App\Actions\DataManagement\SdmManagement\EmployeeManagement;

use App\Models\DataManagement\SdmManagement\EmployeeManagement\Employee;
use Illuminate\Support\Facades\DB;

class CreateEmployee
{
    public function handle(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            return Employee::create($data);
        });
    }
}