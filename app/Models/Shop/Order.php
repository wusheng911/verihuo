<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [];

    public function orderSkus(){
        return $this->hasMany('App\Models\Shop\OrderSku');
    }

    public function customer(){
        return $this->belongsTo('App\Customer');
    }
    
    public function orderStatus(){
        return $this->hasOne('App\Models\Shop\OrderStatus', 'id', 'status');
    }

    public function consigneeZone(){
        return $this->hasOne('App\Models\Zone', 'id', 'consignee_zone_id');
    }

    public function logistic(){
        return $this->hasOne('App\Models\Shop\Logistic', 'id', 'logistic_id');
    }

    public function paymentMethod(){
        return $this->hasOne('App\Models\Shop\PaymentMethod', 'id', 'payment_method_id');
    }

    public function merchant(){
        return $this->hasOne('App\Models\Shop\Merchant', 'id', 'merchant_id');
    }
}
