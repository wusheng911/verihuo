<?php

namespace App\Models\Shop;

use Agent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;

    //品牌名称
    protected $brandName = "";

    /**
     * 商品保存属性
     *
     * @var array
     */
    protected $fillable = ['merchant_id','shop_category_id','name','html_info','info','sn','area','package','show_price','show_min_price','show_max_price','status','is_available'];

    /**
     * 获取商品图片
     */
    public function images()
    {
        return $this->hasMany('App\Models\Shop\ProductImage', 'product_id', 'id');
    }

    /**
     * 获取商品图片
     */
    public function firstImage()
    {
        $device = Agent::isMobile() ? 1 : 2;
        return $this->hasOne('App\Models\Shop\ProductImage', 'product_id', 'id')
            ->where('device', $device)->where('is_first', 1);
    }

    /**
     * 商品品牌名称
     */
    public function getBrandName() {
        if(!empty($this->brandName)){
            return $this->brandName;
        }

        $this->brandName = $this->sku_attributes()
                         ->join('shop_attribute_values', 'sku_attributes.attribute_value_id', '=', 'shop_attribute_values.id')
                         ->join('shop_attributes', 'shop_attribute_values.shop_attribute_id', '=', 'shop_attributes.id')
                         ->where('shop_attributes.name', '=', '品牌')
                         ->pluck('shop_attribute_values.value')
                         ->first();

        return $this->brandName;
    }

    public function sku_attributes() {

        return $this->hasMany('App\Models\Shop\SkuAttribute');
    }

    /**
     * 获取商品所属分类
     */
    public function productCategory()
    {
        return $this->hasOne('App\Models\Shop\Category', 'id', 'shop_category_id');
    }

    /**
     * 获取商品所属分类(包含已被删除分类)
     */
    public function productCategoryWithTrashed()
    {
        return $this->hasOne('App\Models\Shop\Category', 'id', 'shop_category_id')->withTrashed();
    }
}
