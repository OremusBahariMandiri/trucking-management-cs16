<?php

namespace App\Http\Requests\MasterData\Vehicle\BodyType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBodyTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('body_type');

        return [
            'code'      => ['nullable', 'string', 'max:20', Rule::unique('aa05_body_types', 'code')->ignore($id)],
            'name'      => ['required', 'string', 'max:100', Rule::unique('aa05_body_types', 'name')->ignore($id)],
            'is_active' => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', false)]);
    }
}