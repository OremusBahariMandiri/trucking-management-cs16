<?php

namespace App\Http\Requests\DataManagement\SdmManagement\DriverManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $driverId = $this->route('driver')?->id ?? $this->route('driver');

        return [
            'driver_code'           => ['required', 'string', 'max:20', "unique:bb01_drivers,driver_code,{$driverId}"],
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
                Rule::unique('bb01_drivers', 'nik')
                    ->ignore($driverId)
                    ->whereNull('deleted_at'),
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

            'documents'                   => ['nullable', 'array'],
            'documents.*.document_name'   => ['required_with:documents', 'string', 'max:100'],
            'documents.*.document_number' => ['nullable', 'string', 'max:50'],
            'documents.*.issued_date'     => ['nullable', 'date'],
            'documents.*.expiry_date'     => ['nullable', 'date'],
            'documents.*.description'     => ['nullable', 'string', 'max:255'],
            'documents.*.file_path'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],

            'certificates'                      => ['nullable', 'array'],
            'certificates.*.certificate_name'   => ['required_with:certificates', 'string', 'max:100'],
            'certificates.*.certificate_number' => ['nullable', 'string', 'max:50'],
            'certificates.*.issuer'             => ['nullable', 'string', 'max:100'],
            'certificates.*.issued_date'        => ['nullable', 'date'],
            'certificates.*.expiry_date'        => ['nullable', 'date'],
            'certificates.*.description'        => ['nullable', 'string', 'max:255'],
            'certificates.*.file_path'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],

            // Kontak darurat baru
            'emergency_contacts'                        => ['nullable', 'array'],
            'emergency_contacts.*.contact_type'         => ['required_with:emergency_contacts', 'string', 'in:orang_tua,suami,istri,saudara_kandung'],
            'emergency_contacts.*.contact_status'       => ['nullable', 'string', 'in:aktif,tidak_aktif'],
            'emergency_contacts.*.full_name'            => ['required_with:emergency_contacts', 'string', 'max:255'],
            'emergency_contacts.*.nik'                  => ['nullable', 'string', 'size:16'],
            'emergency_contacts.*.birth_place'          => ['nullable', 'string', 'max:100'],
            'emergency_contacts.*.birth_date'           => ['nullable', 'date'],
            'emergency_contacts.*.gender'               => ['nullable', 'string', 'in:male,female'],
            'emergency_contacts.*.religion'             => ['nullable', 'string'],
            'emergency_contacts.*.marital_status'       => ['nullable', 'string'],
            'emergency_contacts.*.phone_primary'        => ['required_with:emergency_contacts', 'string', 'max:20'],
            'emergency_contacts.*.phone_alternative'    => ['nullable', 'string', 'max:20'],
            'emergency_contacts.*.province'             => ['nullable', 'string', 'max:100'],
            'emergency_contacts.*.city'                 => ['nullable', 'string', 'max:100'],
            'emergency_contacts.*.district'             => ['nullable', 'string', 'max:100'],
            'emergency_contacts.*.village'              => ['nullable', 'string', 'max:100'],
            'emergency_contacts.*.rt_rw'                => ['nullable', 'string', 'max:20'],
            'emergency_contacts.*.postal_code'          => ['nullable', 'string', 'max:10'],
            'emergency_contacts.*.full_address'         => ['nullable', 'string', 'max:500'],

            // Kontak darurat yang sudah ada (update)
            'existing_emergency_contacts'                        => ['nullable', 'array'],
            'existing_emergency_contacts.*.contact_type'         => ['sometimes', 'nullable', 'string', 'in:orang_tua,suami,istri,saudara_kandung'],
            'existing_emergency_contacts.*.contact_status'       => ['sometimes', 'nullable', 'string', 'in:aktif,tidak_aktif'],
            'existing_emergency_contacts.*.full_name'            => ['sometimes', 'nullable', 'string', 'max:255'],
            'existing_emergency_contacts.*.nik'                  => ['sometimes', 'nullable', 'string', 'size:16'],
            'existing_emergency_contacts.*.birth_place'          => ['sometimes', 'nullable', 'string', 'max:100'],
            'existing_emergency_contacts.*.birth_date'           => ['sometimes', 'nullable', 'date'],
            'existing_emergency_contacts.*.gender'               => ['sometimes', 'nullable', 'string', 'in:male,female'],
            'existing_emergency_contacts.*.religion'             => ['sometimes', 'nullable', 'string'],
            'existing_emergency_contacts.*.marital_status'       => ['sometimes', 'nullable', 'string'],
            'existing_emergency_contacts.*.phone_primary'        => ['sometimes', 'nullable', 'string', 'max:20'],
            'existing_emergency_contacts.*.phone_alternative'    => ['sometimes', 'nullable', 'string', 'max:20'],
            'existing_emergency_contacts.*.province'             => ['sometimes', 'nullable', 'string', 'max:100'],
            'existing_emergency_contacts.*.city'                 => ['sometimes', 'nullable', 'string', 'max:100'],
            'existing_emergency_contacts.*.district'             => ['sometimes', 'nullable', 'string', 'max:100'],
            'existing_emergency_contacts.*.village'              => ['sometimes', 'nullable', 'string', 'max:100'],
            'existing_emergency_contacts.*.rt_rw'                => ['sometimes', 'nullable', 'string', 'max:20'],
            'existing_emergency_contacts.*.postal_code'          => ['sometimes', 'nullable', 'string', 'max:10'],
            'existing_emergency_contacts.*.full_address'         => ['sometimes', 'nullable', 'string', 'max:500'],
        ];
    }
}
