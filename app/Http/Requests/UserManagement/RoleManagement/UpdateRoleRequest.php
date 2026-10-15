<?php

namespace App\Http\Requests\UserManagement\RoleManagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $roleId = $this->route('role')->id;

        return [
            'name'        => "required|string|max:100|unique:roles,name,{$roleId}",
            'slug'        => "nullable|string|max:100|unique:roles,slug,{$roleId}",
            'description' => 'nullable|string|max:255',
        ];
    }
}