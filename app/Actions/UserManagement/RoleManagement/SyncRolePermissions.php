<?php

namespace App\Actions\UserManagement\RoleManagement;

use App\Models\UserManagement\Role;

class SyncRolePermissions
{
    /**
     * @param  int[]  $permissionIds
     */
    public function execute(Role $role, array $permissionIds): void
    {
        $role->permissions()->sync($permissionIds);
    }
}