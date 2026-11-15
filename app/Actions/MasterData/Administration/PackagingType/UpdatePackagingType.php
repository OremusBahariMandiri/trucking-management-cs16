<?php
namespace App\Actions\MasterData\Administration\PackagingType;
use App\Models\MasterData\Administration\PackagingType;
class UpdatePackagingType
{
    public function execute(PackagingType $model, array $data): PackagingType
    {
        $model->update($data);
        return $model;
    }
}
