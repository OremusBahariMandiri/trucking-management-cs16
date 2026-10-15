<?php

namespace App\Http\Requests\UserManagement\UserManagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'employee_code'      => "nullable|string|max:50|unique:users,employee_code,{$userId}",
            'employee_id_number' => "nullable|string|max:50|unique:users,employee_id_number,{$userId}",
            'full_name'          => 'required|string|max:150',
            'department'         => 'nullable|string|max:100',
            'position'           => 'nullable|string|max:100',
            'work_location'      => 'nullable|string|max:100',
            'password'           => 'nullable|string|min:8|confirmed',
            'role_ids'           => 'nullable|array',
            'role_ids.*'         => 'exists:roles,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'nama lengkap',
            'password'  => 'kata sandi',
            'role_ids'  => 'role',
        ];
    }
}