<?php
namespace App\Http\Requests\MasterData\Administration\PackagingType;
use Illuminate\Foundation\Http\FormRequest;
class UpdatePackagingTypeRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route()->parameter(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', 'PackagingType')));
        return [
            'code'        => 'required|string|max:20|unique:ad07_packaging_types,code,'.($id?->id ?? $id),
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }
}
