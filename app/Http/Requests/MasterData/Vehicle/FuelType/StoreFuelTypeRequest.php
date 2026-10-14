<?php

namespace App\Http\Requests\MasterData\Vehicle\FuelType;

use Illuminate\Foundation\Http\FormRequest;

class StoreFuelTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:100|unique:fuel_types,name',
            'is_active' => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', false)]);
    }
}