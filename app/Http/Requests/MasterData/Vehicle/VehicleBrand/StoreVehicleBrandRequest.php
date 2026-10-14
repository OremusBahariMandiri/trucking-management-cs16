<?php

namespace App\Http\Requests\MasterData\Vehicle\VehicleBrand;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:100|unique:aa02_vehicle_brands,name',
            'country_of_origin' => 'nullable|string|max:100',
            'is_active'         => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', false)]);
    }
}
