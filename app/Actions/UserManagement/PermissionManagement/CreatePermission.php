<?php

namespace App\Actions\UserManagement\PermissionManagement;

use App\Models\UserManagement\Permission;
use Illuminate\Support\Str;

class CreatePermission
{
    public function execute(array $data): Permission
    {
        return Permission::create([
            'name'        => $data['name'],
            'slug'        => $data['slug'] ?? Str::slug($data['name'], '.'),
            'group'       => $data['group'],
            'description' => $data['description'] ?? null,
        ]);
    }
}