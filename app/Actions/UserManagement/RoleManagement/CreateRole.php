<?php

namespace App\Actions\UserManagement\RoleManagement;

use App\Models\UserManagement\Role;
use Illuminate\Support\Str;

class CreateRole
{
    public function execute(array $data): Role
    {
        return Role::create([
            'name'        => $data['name'],
            'slug'        => $data['slug'] ?? Str::slug($data['name']),
            'description' => $data['description'] ?? null,
        ]);
    }
}