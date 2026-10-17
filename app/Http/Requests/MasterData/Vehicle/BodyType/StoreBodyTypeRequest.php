<?php

namespace App\Http\Requests\MasterData\Vehicle\BodyType;

use Illuminate\Foundation\Http\FormRequest;

class StoreBodyTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'      => 'nullable|string|max:20|unique:aa05_body_types,code',
            'name'      => 'required|string|max:100|unique:aa05_body_types,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', false)]);
    }
}