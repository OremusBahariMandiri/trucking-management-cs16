<?php
namespace App\Actions\MasterData\Administration\GoodsType;
use App\Models\MasterData\Administration\GoodsType;
class CreateGoodsType
{
    public function execute(array $data): GoodsType
    {
        return GoodsType::create($data);
    }
}
