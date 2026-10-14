<?php

namespace App\Http\Requests\DataManagement\SdmManagement\DriverManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'driver_code'           => ['required', 'string', 'max:20', 'unique:bb01_drivers,driver_code'],
            'full_name'             => ['required', 'string', 'max:100'],
            'gender'                => ['required', 'in:male,female'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'email'                 => ['nullable', 'email', 'max:100'],
            'birth_date'            => ['nullable', 'date'],
            'birth_place'           => ['nullable', 'string', 'max:100'],
            'marital_status'        => ['nullable', 'in:single,married,divorced,widowed'],
            'children_count'        => ['nullable', 'integer', 'min:0'],
            'status'                => ['required', 'in:active,inactive,resigned'],
            'join_date'             => ['nullable', 'date'],
            'photo_path'            => ['nullable', 'image', 'max:2048'],

            'nik' => [
                'nullable',
                'string',
                'size:16',
                Rule::unique('bb01_drivers', 'nik')->whereNull('deleted_at'),
            ],
            'ktp_file_path'         => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'ktp_address'           => ['nullable', 'string'],
            'ktp_village'           => ['nullable', 'string', 'max:100'],
            'ktp_district'          => ['nullable', 'string', 'max:100'],
            'ktp_city'              => ['nullable', 'string', 'max:100'],
            'ktp_province'          => ['nullable', 'string', 'max:100'],
            'ktp_postal_code'       => ['nullable', 'string', 'max:10'],

            'domicile_same_as_ktp'  => ['nullable', 'boolean'],
            'domicile_address'      => ['nullable', 'string'],
            'domicile_village'      => ['nullable', 'string', 'max:100'],
            'domicile_district'     => ['nullable', 'string', 'max:100'],
            'domicile_city'         => ['nullable', 'string', 'max:100'],
            'domicile_province'     => ['nullable', 'string', 'max:100'],
            'domicile_postal_code'  => ['nullable', 'string', 'max:10'],

            'notes'                 => ['nullable', 'string'],

            'documents'             => ['nullable', 'array'],
            'documents.*.document_name'   => ['required_with:documents', 'string', 'max:100'],
            'documents.*.document_number' => ['nullable', 'string', 'max:50'],
            'documents.*.issued_date'     => ['nullable', 'date'],
            'documents.*.expiry_date'     => ['nullable', 'date'],
            'documents.*.description'     => ['nullable', 'string', 'max:255'],
            'documents.*.file_path'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],

            'certificates'                => ['nullable', 'array'],
            'certificates.*.certificate_name'   => ['required_with:certificates', 'string', 'max:100'],
            'certificates.*.certificate_number' => ['nullable', 'string', 'max:50'],
            'certificates.*.issuer'             => ['nullable', 'string', 'max:100'],
            'certificates.*.issued_date'        => ['nullable', 'date'],
            'certificates.*.expiry_date'        => ['nullable', 'date'],
            'certificates.*.description'        => ['nullable', 'string', 'max:255'],
            'certificates.*.file_path'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
