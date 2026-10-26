<?php
namespace App\Actions\MasterData\Administration\GoodsType;
use App\Models\MasterData\Administration\GoodsType;
class UpdateGoodsType
{
    public function execute(GoodsType $model, array $data): GoodsType
    {
        $model->update($data);
        return $model;
    }
}
