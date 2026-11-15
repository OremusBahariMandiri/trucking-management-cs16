<?php
namespace App\Actions\MasterData\Administration\GoodsCategory;
use App\Models\MasterData\Administration\GoodsCategory;
class UpdateGoodsCategory
{
    public function execute(GoodsCategory $model, array $data): GoodsCategory
    {
        $model->update($data);
        return $model;
    }
}
