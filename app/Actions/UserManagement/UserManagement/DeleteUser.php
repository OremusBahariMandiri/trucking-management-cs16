<?php

namespace App\Actions\UserManagement\UserManagement;

use App\Models\User;

class DeleteUser
{
    public function execute(User $user): void
    {
        $user->roles()->detach();
        $user->delete();
    }
}