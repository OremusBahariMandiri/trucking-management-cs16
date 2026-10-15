<?php

namespace App\Http\Requests\UserManagement\UserManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'employee_code'      => 'nullable|string|max:50|unique:users,employee_code',
            'employee_id_number' => 'nullable|string|max:50|unique:users,employee_id_number',
            'full_name'          => 'required|string|max:150',
            'department'         => 'nullable|string|max:100',
            'position'           => 'nullable|string|max:100',
            'work_location'      => 'nullable|string|max:100',
            'password'           => 'required|string|min:8|confirmed',
            'role_ids'           => 'nullable|array',
            'role_ids.*'         => 'exists:roles,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'employee_code'      => 'kode karyawan',
            'employee_id_number' => 'NIK',
            'full_name'          => 'nama lengkap',
            'password'           => 'kata sandi',
            'password_confirmation' => 'konfirmasi kata sandi',
            'role_ids'           => 'role',
        ];
    }
}