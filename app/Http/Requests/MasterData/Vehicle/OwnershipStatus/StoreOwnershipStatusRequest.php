<?php

namespace App\Http\Requests\MasterData\Vehicle\OwnershipStatus;

use Illuminate\Foundation\Http\FormRequest;

class StoreOwnershipStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'        => 'nullable|string|max:10|unique:aa10_ownership_statuses,code',
            'name'        => 'required|string|max:100|unique:aa10_ownership_statuses,name',
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
