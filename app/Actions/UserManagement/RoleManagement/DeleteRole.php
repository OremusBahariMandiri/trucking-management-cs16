<?php

namespace App\Actions\UserManagement\RoleManagement;

use App\Models\UserManagement\Role;

class DeleteRole
{
    public function execute(Role $role): void
    {
        // Hapus relasi dulu sebelum delete
        $role->permissions()->detach();
        $role->users()->detach(); // user_roles
        $role->delete();
    }
}