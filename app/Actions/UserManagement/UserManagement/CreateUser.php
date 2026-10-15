<?php

namespace App\Actions\UserManagement\UserManagement;

use App\Models\User;

class CreateUser
{
    public function execute(array $data): User
    {
        return User::create([
            'employee_code'      => $data['employee_code'] ?? null,
            'employee_id_number' => $data['employee_id_number'] ?? null,
            'full_name'          => $data['full_name'],
            'department'         => $data['department'] ?? null,
            'position'           => $data['position'] ?? null,
            'work_location'      => $data['work_location'] ?? null,
            'password'           => $data['password'],
        ]);
    }
}