<?php

namespace App\Http\Requests\DataManagement\FleetManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreFleetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // atur permission di sini atau di controller
    }

    public function rules(): array
    {
        return [
            // ── Tab 1: Informasi Umum ──────────────────────────────────────
            'vehicle_code'      => ['nullable', 'string', 'max:20', 'unique:aa06_vehicles,vehicle_code'],
            'police_number'     => ['nullable', 'string', 'max:20', 'unique:aa06_vehicles,police_number'],
            'hull_number'       => ['nullable', 'string', 'max:30', 'unique:aa06_vehicles,hull_number'],
            'vehicle_type_id'   => ['nullable', 'exists:aa01_vehicle_types,id'],
            'vehicle_brand_id'  => ['nullable', 'exists:aa02_vehicle_brands,id'],
            'vehicle_model_id'  => ['nullable', 'exists:aa03_vehicle_models,id'],
            'model_name'        => ['nullable', 'string', 'max:100'],
            'manufacture_year'  => ['nullable', 'integer', 'min:1980', 'max:' . now()->year],
            'color'             => ['nullable', 'string', 'max:50'],
            'vehicle_status'    => ['nullable', 'in:active,inactive,service,broken,sold'],

            // ── Tab 2: Spesifikasi Teknis ──────────────────────────────────
            'chassis_number'            => ['nullable', 'string', 'max:50', 'unique:aa06_vehicles,chassis_number'],
            'engine_number'             => ['nullable', 'string', 'max:50', 'unique:aa06_vehicles,engine_number'],
            'fuel_type_id'              => ['nullable', 'exists:aa04_fuel_types,id'],
            'engine_capacity_cc'        => ['nullable', 'integer', 'min:0'],
            'axis_configuration'        => ['nullable', 'in:4x2,6x2,6x4,8x4,10x4,other'],
            'wheel_count'               => ['nullable', 'integer', 'min:2', 'max:24'],
            'load_capacity_ton'         => ['nullable', 'numeric', 'min:0'],
            'gross_vehicle_weight_ton'  => ['nullable', 'numeric', 'min:0'],
            'tare_weight_ton'           => ['nullable', 'numeric', 'min:0'],
            'body_type_id'              => ['nullable', 'exists:aa05_body_types,id'],
            'body_dimension'            => ['nullable', 'string', 'max:50'],

            // ── Tab 3: Kepemilikan & Penugasan ─────────────────────────────
            'ownership_status'  => ['nullable', 'in:company,rental,leasing,kerjasama'],
            'acquisition_year'  => ['nullable', 'integer', 'min:1980', 'max:' . now()->year],
            'acquisition_value' => ['nullable', 'numeric', 'min:0'],
            'leasing_company'   => ['nullable', 'string', 'max:100'],
            'leasing_end_date'  => ['nullable', 'date'],
            'department_id'     => ['nullable', 'integer'],
            'branch_id'         => ['nullable', 'integer'],
            'driver_id'         => ['nullable', 'exists:users,id'],

            // ── Tab 4: Operasional ─────────────────────────────────────────
            'last_odometer_km'      => ['nullable', 'integer', 'min:0'],
            'last_service_date'     => ['nullable', 'date'],
            'service_interval_km'   => ['nullable', 'integer', 'min:0'],
            'gps_tracker_id'        => ['nullable', 'string', 'max:100'],
            'gps_provider'          => ['nullable', 'string', 'max:100'],
            'gps_status'            => ['nullable', 'in:active,offline,not_installed'],

            // ── Tab 5: Dokumen & Legalitas ─────────────────────────────────
            'stnk_number'               => ['nullable', 'string', 'max:30'],
            'tax_expiry_date'           => ['nullable', 'date'],
            'kir_number'                => ['nullable', 'string', 'max:30'],
            'kir_expiry_date'           => ['nullable', 'date'],
            'bpkb_number'               => ['nullable', 'string', 'max:30'],
            'insurance_policy_number'   => ['nullable', 'string', 'max:50'],
            'insurance_company'         => ['nullable', 'string', 'max:100'],
            'insurance_expiry_date'     => ['nullable', 'date'],

            // ── Tab 6: Foto Kendaraan ──────────────────────────────────────
            'photos'                => ['nullable', 'array', 'max:20'],
            'photos.*.file'         => ['required_with:photos', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // max 5MB
            'photos.*.category'     => ['nullable', 'string'],
            'photos.*.caption'      => ['nullable', 'string', 'max:255'],

            // ── Tab 7: Catatan ─────────────────────────────────────────────
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'vehicle_code'     => 'Kode Kendaraan',
            'police_number'    => 'Nomor Polisi',
            'hull_number'      => 'Nomor Lambung',
            'vehicle_type_id'  => 'Jenis Kendaraan',
            'vehicle_brand_id' => 'Merek Kendaraan',
            'manufacture_year' => 'Tahun Pembuatan',
            'chassis_number'   => 'Nomor Rangka',
            'engine_number'    => 'Nomor Mesin',
            'photos.*.file'    => 'File Foto',
        ];
    }
}