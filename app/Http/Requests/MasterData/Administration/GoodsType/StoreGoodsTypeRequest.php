<?php
namespace App\Http\Requests\MasterData\Administration\GoodsType;
use Illuminate\Foundation\Http\FormRequest;
class StoreGoodsTypeRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'code'        => 'required|string|max:20|unique:ad05_goods_types,code',
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }
}
