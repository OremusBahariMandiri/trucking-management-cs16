<?php

namespace App\Http\Requests\MasterData\Vehicle\VehicleBrand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('vehicle_brand');

        return [
            'name'              => ['required', 'string', 'max:100', Rule::unique('vehicle_brands', 'name')->ignore($id)],
            'country_of_origin' => 'nullable|string|max:100',
            'is_active'         => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', false)]);
    }
}
