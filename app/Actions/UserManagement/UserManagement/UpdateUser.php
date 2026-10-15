<?php

namespace App\Actions\UserManagement\UserManagement;

use App\Models\User;

class UpdateUser
{
    public function execute(User $user, array $data): User
    {
        $payload = [
            'employee_code'      => $data['employee_code'] ?? $user->employee_code,
            'employee_id_number' => $data['employee_id_number'] ?? null,
            'full_name'          => $data['full_name'],
            'department'         => $data['department'] ?? null,
            'position'           => $data['position'] ?? null,
            'work_location'      => $data['work_location'] ?? null,
        ];

        // Password hanya diupdate jika diisi
        if (!empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);

        return $user->fresh();
    }
}