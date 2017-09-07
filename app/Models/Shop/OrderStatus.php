<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_status';
    const nextR = [
        OrderStatus::Raw => OrderStatus::Confirmed,
        OrderStatus::Confirmed => OrderStatus::Preparing,
        OrderStatus::Preparing => OrderStatus::Shipping,
        OrderStatus::Shipping => OrderStatus::Checked,
    ];

    const Error = 0;
    const Raw = 1;
    const Completed = 2;
    const Confirmed = 3;
    const Preparing = 4;
    const Shipping = 5;
    const Checked = 6;
    const Cancelled = 7;

    public static function next($current){
        return self::nextR[$current];
    }
}
