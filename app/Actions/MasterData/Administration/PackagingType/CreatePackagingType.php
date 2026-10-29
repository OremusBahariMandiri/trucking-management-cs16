<?php
namespace App\Actions\MasterData\Administration\PackagingType;
use App\Models\MasterData\Administration\PackagingType;
class CreatePackagingType
{
    public function execute(array $data): PackagingType
    {
        return PackagingType::create($data);
    }
}
