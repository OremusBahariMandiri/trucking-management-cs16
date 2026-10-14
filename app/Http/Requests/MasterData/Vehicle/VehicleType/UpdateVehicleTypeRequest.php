<?php

namespace App\Http\Requests\MasterData\Vehicle\VehicleType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('vehicle_type');

        return [
            'code'        => ['nullable', 'string', 'max:20', Rule::unique('vehicle_types', 'code')->ignore($id)],
            'name'        => ['required', 'string', 'max:100', Rule::unique('vehicle_types', 'name')->ignore($id)],
            'description' => 'nullable|string|max:500',
            'is_active'   => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', false)]);
    }
}