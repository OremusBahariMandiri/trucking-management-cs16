<?php
namespace App\Actions\MasterData\Administration\PackagingType;
use App\Models\MasterData\Administration\PackagingType;
class DeletePackagingType
{
    public function execute(PackagingType $model): void
    {
        $model->delete();
    }
}
