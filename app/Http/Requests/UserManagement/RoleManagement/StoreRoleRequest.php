<?php

namespace App\Http\Requests\UserManagement\RoleManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:100|unique:roles,name',
            'slug'        => 'nullable|string|max:100|unique:roles,slug',
            'description' => 'nullable|string|max:255',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->slug)) {
            $this->merge(['slug' => Str::slug($this->name)]);
        }
    }
}