<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Logistic\KDNiaoLogistic;

class Logis extends Facade{
    protected static function getFacadeAccessor()
    {
        return new KDNiaoLogistic();
        // return 'Logis';
    }
}