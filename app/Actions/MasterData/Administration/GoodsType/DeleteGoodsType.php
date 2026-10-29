<?php
namespace App\Actions\MasterData\Administration\GoodsType;
use App\Models\MasterData\Administration\GoodsType;
class DeleteGoodsType
{
    public function execute(GoodsType $model): void
    {
        $model->delete();
    }
}
