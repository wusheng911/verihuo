<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkuAttribute extends Model
{
    use SoftDeletes;

    protected $fillable = ['sku_id','product_id','attribute_value_id','attribute_id','is_sell'];

    function attributeValue(){
        return $this->hasOne('App\Models\Shop\AttributeValue', 'id', 'attribute_value_id');
    }
}