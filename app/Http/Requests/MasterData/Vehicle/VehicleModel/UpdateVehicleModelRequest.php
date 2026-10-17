<?php

namespace App\Http\Requests\MasterData\Vehicle\VehicleModel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('vehicle_model');

        return [
            'vehicle_brand_id' => 'required|exists:aa02_vehicle_brands,id',
            'vehicle_type_id'  => 'nullable|exists:aa01_vehicle_types,id',   // ← tambah ini
            'code'        => ['nullable', 'string', 'max:10', Rule::unique('aa03_vehicle_models', 'code')->ignore($id)],
            'name'             => [
                'required',
                'string',
                'max:100',
                Rule::unique('aa03_vehicle_models')->where(
                    fn($q) => $q->where('vehicle_brand_id', $this->vehicle_brand_id)
                )->ignore($id),
            ],
            'description' => 'nullable|string|max:1000',
            'year_start'       => 'nullable|integer|min:1900|max:' . (date('Y') + 2),
            'year_end'         => 'nullable|integer|min:1900|max:' . (date('Y') + 5) . '|gte:year_start',
            'is_active'        => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_brand_id.required' => 'Merek kendaraan wajib dipilih.',
            'vehicle_brand_id.exists'   => 'Merek kendaraan tidak ditemukan.',
            'year_end.gte'              => 'Tahun akhir harus lebih besar atau sama dengan tahun mulai.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', false)]);
    }
}
