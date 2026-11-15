<?php
namespace App\Http\Requests\MasterData\Administration\PackagingType;
use Illuminate\Foundation\Http\FormRequest;
class StorePackagingTypeRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'code'        => 'required|string|max:20|unique:ad07_packaging_types,code',
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }
}
