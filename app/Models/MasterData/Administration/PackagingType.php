<?php
namespace App\Models\MasterData\Administration;
use Illuminate\Database\Eloquent\Model;

class PackagingType extends Model
{
    protected $table = 'ad07_packaging_types';
    protected $fillable = ['code','name','description','is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
