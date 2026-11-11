<?php

namespace App\Http\Requests\MasterData\Administration\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'registration_date'  => ['required', 'date'],
            'customer_code'      => ['required', 'string', 'max:30', 'unique:ad03_customers,customer_code'],
            'name'               => ['required', 'string', 'max:150'],
            'customer_type_id'   => ['nullable', 'exists:ad01_customer_types,id'],
            'business_type_id'   => ['nullable', 'exists:ad02_business_types,id'],
            'npwp_number'        => ['nullable', 'string', 'max:30'],
            'npwp_file'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'nib_number'         => ['nullable', 'string', 'max:30'],
            'nib_file'           => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'address'            => ['nullable', 'string'],
            'rt_rw'              => ['nullable', 'string', 'max:10'],
            'kelurahan'          => ['nullable', 'string', 'max:100'],
            'kecamatan'          => ['nullable', 'string', 'max:100'],
            'city'               => ['nullable', 'string', 'max:100'],
            'province'           => ['nullable', 'string', 'max:100'],
            'postal_code'        => ['nullable', 'string', 'max:10'],
            'phone'              => ['nullable', 'string', 'max:30'],
            'email'              => ['nullable', 'email', 'max:150'],
            'is_active'          => ['boolean'],
            // PIC
            'pics'               => ['nullable', 'array'],
            'pics.*.name'        => ['required_with:pics.*.phone', 'nullable', 'string', 'max:150'],
            'pics.*.position'    => ['nullable', 'string', 'max:100'],
            'pics.*.department'  => ['nullable', 'string', 'max:100'],
            'pics.*.phone'       => ['nullable', 'string', 'max:30'],
            'pics.*.email'       => ['nullable', 'email', 'max:150'],
            'pics.*.is_primary'  => ['boolean'],
        ];
    }
}
