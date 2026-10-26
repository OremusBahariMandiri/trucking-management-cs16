<?php
namespace App\Http\Requests\MasterData\Administration\GoodsType;
use Illuminate\Foundation\Http\FormRequest;
class UpdateGoodsTypeRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route()->parameter(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', 'GoodsType')));
        return [
            'code'        => 'required|string|max:20|unique:ad05_goods_types,code,'.($id?->id ?? $id),
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }
}
