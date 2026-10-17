<?php

namespace App\Actions\UserManagement\UserRoleManagement;

use App\Models\User;

class AssignUserRoles
{
    /**
     * @param  int[]  $roleIds  — sync (replace semua role lama)
     */
    public function execute(User $user, array $roleIds): void
    {
        $user->roles()->sync($roleIds);
    }
}