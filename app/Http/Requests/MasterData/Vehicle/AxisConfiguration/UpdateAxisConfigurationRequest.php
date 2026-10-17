<?php

namespace App\Http\Requests\MasterData\Vehicle\AxisConfiguration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAxisConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('axis_configuration')?->id ?? $this->route('axis_configuration');

        return [
            'name'        => ['required', 'string', 'max:30',  Rule::unique('aa09_vehicle_axis_configurations', 'name')->ignore($id)],
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
