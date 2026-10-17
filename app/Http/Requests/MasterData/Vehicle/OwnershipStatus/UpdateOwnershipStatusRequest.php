<?php

namespace App\Http\Requests\MasterData\Vehicle\OwnershipStatus;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOwnershipStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('ownership_status')?->id ?? $this->route('ownership_status');

        return [
            'code'        => ['nullable', 'string', 'max:10', Rule::unique('aa10_ownership_statuses', 'code')->ignore($id)],
            'name'        => ['required', 'string', 'max:100', Rule::unique('aa10_ownership_statuses', 'name')->ignore($id)],
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
