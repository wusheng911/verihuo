<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    public function zone(){
        return $this->hasOne('App\Models\Zone', 'id', 'zone_id');
    }

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['customer_id','zone_id','consignee_name','consignee_phone','details','is_default'];
}
