<?php

namespace App\Actions\UserManagement\PermissionManagement;

use App\Models\UserManagement\Permission;

class DeletePermission
{
    public function execute(Permission $permission): void
    {
        $permission->roles()->detach();
        $permission->delete();
    }
}