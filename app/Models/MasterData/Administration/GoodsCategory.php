<?php
namespace App\Models\MasterData\Administration;
use Illuminate\Database\Eloquent\Model;

class GoodsCategory extends Model
{
    protected $table = 'ad06_goods_categories';
    protected $fillable = ['code','name','description','is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
