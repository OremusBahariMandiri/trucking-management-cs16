<?php
namespace App\Actions\MasterData\Administration\GoodsCategory;
use App\Models\MasterData\Administration\GoodsCategory;
class DeleteGoodsCategory
{
    public function execute(GoodsCategory $model): void
    {
        $model->delete();
    }
}
