<?php

namespace App\Http\Requests\UserManagement\PermissionManagement;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:150',
            'slug'        => 'required|string|max:150|unique:permissions,slug',
            'group'       => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ];
    }
}