<?php

namespace App\Http\Requests\MasterData\Vehicle\AxisConfiguration;

use Illuminate\Foundation\Http\FormRequest;

class StoreAxisConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'        => 'nullable|string|max:10|unique:aa09_vehicle_axis_configurations,code',
            'name'        => 'required|string|max:30|unique:aa09_vehicle_axis_configurations,name',
            'description' => 'nullable|string|max:200',
            'is_active'   => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', false),
        ]);
    }
}
