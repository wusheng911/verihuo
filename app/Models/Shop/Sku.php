<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends Model
{
    use SoftDeletes;

    /**
     * 商品保存属性
     *
     * @var array
     */
    protected $fillable = ['product_id','merchant_id','sn','quantity','price','sale_attribute_values','attribute_values'];

    /*
     * 销售属性组合字符串: 红色,S码
     */
    public function saleAttrStr(){
        $attrs = $this->skuAttributes()->where('is_sell', '=', 1)->get();
        $str = $attrs->reduce(function($c, $a){
            return $c.' '.$a->attributeValue->value;
        }, "");
        return $str;
    }

    public function skuAttributes(){
        return $this->hasMany('App\Models\Shop\SkuAttribute');
    }
}
