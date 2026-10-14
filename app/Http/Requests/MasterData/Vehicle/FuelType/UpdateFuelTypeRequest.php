<?php

namespace App\Http\Requests\MasterData\Vehicle\FuelType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFuelTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('fuel_type');

        return [
            'name'      => ['required', 'string', 'max:100', Rule::unique('fuel_types', 'name')->ignore($id)],
            'is_active' => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', false)]);
    }
}