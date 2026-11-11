<?php

namespace App\Http\Requests\MasterData\Administration\CustomerType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerTypeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'string', 'max:20', 'unique:ad01_customer_types,code'],
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ];
    }
}
