<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Order\DefaultOrder;

class Ord extends Facade{
    protected static function getFacadeAccessor()
    {
        return new DefaultOrder();
    }
}