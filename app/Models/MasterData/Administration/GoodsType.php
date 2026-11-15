<?php
namespace App\Models\MasterData\Administration;
use Illuminate\Database\Eloquent\Model;

class GoodsType extends Model
{
    protected $table = 'ad05_goods_types';
    protected $fillable = ['code','name','description','is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
