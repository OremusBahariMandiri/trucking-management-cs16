<?php

namespace App\Actions\UserManagement\PermissionManagement;

use App\Models\UserManagement\Permission;

class UpdatePermission
{
    public function execute(Permission $permission, array $data): Permission
    {
        $permission->update([
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'group'       => $data['group'],
            'description' => $data['description'] ?? null,
        ]);

        return $permission->fresh();
    }
}