<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'shop_attributes';

    /**
     * 商品分类属性值
     */
    public function getAttributeValues()
    {
        return $this->hasMany('App\Models\Shop\AttributeValue', 'shop_attribute_id', 'id');
    }
}
