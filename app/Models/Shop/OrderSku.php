<?php

namespace App\Models\Shop;

use Agent;
use Illuminate\Database\Eloquent\Model;

class OrderSku extends Model
{
    public $timestamps = false;

    public function sku(){
        return $this->hasOne('App\Models\Shop\Sku', 'id', 'sku_id')->withTrashed();
    }

    /*
     * 获取产品头图
     */
    public function productFirstImage(){
        $device = Agent::isMobile() ? 1 : 2;
        return $this->hasOne('App\Models\Shop\ProductImage', 'product_id', 'product_id')->where('device', $device)->where('is_first',1);
    }

    public function product(){
        return $this->hasOne('App\Models\Shop\Product', 'id', 'product_id')->withTrashed();
    }
}