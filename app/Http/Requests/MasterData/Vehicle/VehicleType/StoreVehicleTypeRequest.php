<?php

namespace App\Http\Requests\MasterData\Vehicle\VehicleType;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'        => 'nullable|string|max:20|unique:aa01_vehicle_types,code',
            'name'        => 'required|string|max:100|unique:aa01_vehicle_types,name',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', false)]);
    }
}