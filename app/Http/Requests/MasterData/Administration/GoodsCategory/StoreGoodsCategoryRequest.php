<?php
namespace App\Http\Requests\MasterData\Administration\GoodsCategory;
use Illuminate\Foundation\Http\FormRequest;
class StoreGoodsCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'code'        => 'required|string|max:20|unique:ad06_goods_categories,code',
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }
}
