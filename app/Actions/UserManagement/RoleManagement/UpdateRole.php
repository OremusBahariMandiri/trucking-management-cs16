<?php

namespace App\Actions\UserManagement\RoleManagement;

use App\Models\UserManagement\Role;
use Illuminate\Support\Str;

class UpdateRole
{
    public function execute(Role $role, array $data): Role
    {
        $role->update([
            'name'        => $data['name'],
            'slug'        => $data['slug'] ?? Str::slug($data['name']),
            'description' => $data['description'] ?? null,
        ]);

        return $role->fresh();
    }
}