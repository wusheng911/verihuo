<?php namespace App\Logistic;

/**
 * 请按照下面结构实现具体类
 *
 * "EBusinessID": "1267587",
 *  "ShipperCode": "SF",
 *  "Success": true,
 *  "LogisticCode": "923807206754",
 *  "State": "4",
 *  "Traces": [
 *    {
 *      "AcceptTime": "2016-10-27 12:23:19",
 *      "AcceptStation": "顺丰速运 已收取快件"
 *    },
*/

abstract class LogisticBase{

    protected $info = null;

    protected $traces = [];

    public function __get($name){
        if($name == "traces"){
            return $this->traces;
        }
    }
}