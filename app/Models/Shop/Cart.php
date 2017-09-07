<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //
    protected $guarded = ['id'];
    protected $table = 'carts';

    public function sku(){
        return $this->hasOne('App\Models\Shop\Sku', 'id', 'sku_id');
    }

    /**
     * 获取对应商家
     */
    public function merchant() {
        return $this->hasOne('App\Models\Shop\Merchant', 'id', 'merchant_id');
    }
}
