<?php

namespace App\Http\Requests\DataManagement\SdmManagement\EmployeeManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nrk'        => ['required', 'string', 'max:20', 'unique:bc01_employees,nrk'],
            'full_name'  => ['required', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'position'   => ['nullable', 'string', 'max:100'],
            'wilker'     => ['nullable', 'string', 'max:100'],
            'notes'      => ['nullable', 'string'],
        ];
    }
}