<?php
namespace App\Http\Requests\MasterData\Administration\GoodsCategory;
use Illuminate\Foundation\Http\FormRequest;
class UpdateGoodsCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route()->parameter(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', 'GoodsCategory')));
        return [
            'code'        => 'required|string|max:20|unique:ad06_goods_categories,code,'.($id?->id ?? $id),
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }
}
