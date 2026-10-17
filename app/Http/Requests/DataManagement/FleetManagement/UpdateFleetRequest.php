<?php

namespace App\Http\Requests\DataManagement\FleetManagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFleetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleId = $this->route('fleet')?->id ?? $this->route('fleet');

        return [
            // ── Tab 1: Informasi Umum ──────────────────────────────────────
            'vehicle_code'      => ['nullable', 'string', 'max:20', "unique:aa06_vehicles,vehicle_code,{$vehicleId}"],
            'police_number'     => ['nullable', 'string', 'max:20', "unique:aa06_vehicles,police_number,{$vehicleId}"],
            'hull_number'       => ['nullable', 'string', 'max:30', "unique:aa06_vehicles,hull_number,{$vehicleId}"],
            'vehicle_type_id'   => ['nullable', 'exists:aa01_vehicle_types,id'],
            'vehicle_brand_id'  => ['nullable', 'exists:aa02_vehicle_brands,id'],
            'vehicle_model_id'  => ['nullable', 'exists:aa03_vehicle_models,id'],
            'model_name'        => ['nullable', 'string', 'max:100'],
            'manufacture_year'  => ['nullable', 'integer', 'min:1980', 'max:' . now()->year],
            'color'             => ['nullable', 'string', 'max:50'],
            'vehicle_status'    => ['nullable', 'in:active,inactive,service,broken,sold'],

            // ── Tab 2: Spesifikasi Teknis ──────────────────────────────────
            'chassis_number'           => ['nullable', 'string', 'max:50', "unique:aa06_vehicles,chassis_number,{$vehicleId}"],
            'engine_number'            => ['nullable', 'string', 'max:50', "unique:aa06_vehicles,engine_number,{$vehicleId}"],
            'fuel_type_id'             => ['nullable', 'exists:aa04_fuel_types,id'],
            'engine_capacity_cc'       => ['nullable', 'integer', 'min:0'],
            'axis_configuration'       => ['nullable', 'exists:aa09_vehicle_axis_configurations,id'], // ← FIX
            'wheel_count'              => ['nullable', 'integer', 'min:2', 'max:24'],
            'load_capacity_ton'        => ['nullable', 'numeric', 'min:0'],
            'gross_vehicle_weight_ton' => ['nullable', 'numeric', 'min:0'],
            'tare_weight_ton'          => ['nullable', 'numeric', 'min:0'],
            'body_type_id'             => ['nullable', 'exists:aa05_body_types,id'],
            'body_dimension'           => ['nullable', 'string', 'max:50'],

            // ── Tab 3: Kepemilikan & Penugasan ─────────────────────────────
            'ownership_status'  => ['nullable', 'exists:aa10_ownership_statuses,id'],           // ← FIX
            'acquisition_year'  => ['nullable', 'integer', 'min:1980', 'max:' . now()->year],
            'acquisition_value' => ['nullable', 'numeric', 'min:0'],
            'leasing_company'   => ['nullable', 'string', 'max:100'],
            'leasing_end_date'  => ['nullable', 'date'],

            // ── Tab 4: Operasional ─────────────────────────────────────────
            'initial_odometer_km'   => ['nullable', 'integer', 'min:0'],  // ← TAMBAH
            'initial_odometer_date' => ['nullable', 'date'],              // ← TAMBAH
            'last_odometer_km'      => ['nullable', 'integer', 'min:0'],
            'last_odometer_date'    => ['nullable', 'date'],              // ← TAMBAH
            'last_service_date'     => ['nullable', 'date'],
            'service_interval_km'   => ['nullable', 'integer', 'min:0'],
            'gps_tracker_id'        => ['nullable', 'string', 'max:100'],
            'gps_provider'          => ['nullable', 'string', 'max:100'],
            'gps_status'            => ['nullable', 'in:active,offline,not_installed'],

            // ── Tab 5: Dokumen & Legalitas ─────────────────────────────────
            'stnk_number'             => ['nullable', 'string', 'max:30'],
            'tax_expiry_date'         => ['nullable', 'date'],
            'kir_number'              => ['nullable', 'string', 'max:30'],
            'kir_expiry_date'         => ['nullable', 'date'],
            'bpkb_number'             => ['nullable', 'string', 'max:30'],
            'insurance_policy_number' => ['nullable', 'string', 'max:50'],
            'insurance_company'       => ['nullable', 'string', 'max:100'],
            'insurance_expiry_date'   => ['nullable', 'date'],

            // ── Tab 5: File Lampiran Dokumen ───────────────────────────────
            'document_files'          => ['nullable', 'array'],
            'document_files.stnk'     => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'document_files.kir'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'document_files.bpkb'     => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'document_files.asuransi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],

            // ── Tab 6: Foto ────────────────────────────────────────────────
            'photos'            => ['nullable', 'array', 'max:20'],
            'photos.*.file'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'photos.*.category' => ['nullable', 'string'],
            'photos.*.caption'  => ['nullable', 'string', 'max:255'],
            'photos_primary'    => ['nullable', 'integer', 'min:0'],

            // ── Tab 7: Catatan ─────────────────────────────────────────────
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'vehicle_code'            => 'Kode Kendaraan',
            'police_number'           => 'Nomor Polisi',
            'hull_number'             => 'Nomor Lambung',
            'chassis_number'          => 'Nomor Rangka',
            'engine_number'           => 'Nomor Mesin',
            'photos.*.file'           => 'File Foto',
            'document_files.stnk'     => 'File STNK',
            'document_files.kir'      => 'File KIR',
            'document_files.bpkb'     => 'File BPKB',
            'document_files.asuransi' => 'File Asuransi',
        ];
    }

    public function preparedPhotos(): array
    {
        $photosData = $this->input('photos');
        if (empty($photosData) || ! is_array($photosData)) {
            return [];
        }

        $primaryIdx = (int) $this->input('photos_primary', 0);
        $photos = [];
        $order  = 0;

        foreach ($photosData as $index => $item) {
            $file = $this->file("photos.{$index}.file");
            if (! ($file instanceof \Illuminate\Http\UploadedFile) || ! $file->isValid()) {
                continue;
            }

            $photos[] = [
                'file'       => $file,
                'category'   => ($item['category'] ?? null) ?: 'lainnya',
                'caption'    => $item['caption'] ?? null,
                'is_primary' => ((int) $index === $primaryIdx),
                'sort_order' => $order++,
            ];
        }

        return $photos;
    }

    public function preparedDocumentFiles(): array
    {
        if (! $this->hasFile('document_files')) {
            return [];
        }

        $result = [];
        foreach ($this->file('document_files') as $type => $file) {
            if ($file && $file->isValid()) {
                $result[$type] = $file;
            }
        }

        return $result;
    }

    protected function prepareForValidation(): void
    {
        $integerFields = [
            'manufacture_year',
            'engine_capacity_cc',
            'axis_configuration',
            'wheel_count',
            'ownership_status',
            'acquisition_year',
            'initial_odometer_km',
            'last_odometer_km',
            'service_interval_km',
        ];

        $merge = [];
        foreach ($integerFields as $field) {
            $value = $this->input($field);
            if ($value !== null && $value !== '') {
                $merge[$field] = (int) $value; // "00001" → 1, "  5  " → 5
            }
        }

        if (!empty($merge)) {
            $this->merge($merge);
        }
    }
}
