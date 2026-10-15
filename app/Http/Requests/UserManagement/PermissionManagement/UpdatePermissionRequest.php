<?php

namespace App\Http\Requests\UserManagement\PermissionManagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $permId = $this->route('permission')->id;

        return [
            'name'        => 'required|string|max:150',
            'slug'        => "required|string|max:150|unique:permissions,slug,{$permId}",
            'group'       => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ];
    }
}