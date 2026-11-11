<?php
namespace App\Actions\MasterData\Administration\GoodsCategory;
use App\Models\MasterData\Administration\GoodsCategory;
class CreateGoodsCategory
{
    public function execute(array $data): GoodsCategory
    {
        return GoodsCategory::create($data);
    }
}
