<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    protected $fillable = ['order_id','remark','info'];

    public function nextVersionId(){
        return (int) $this->version + 1;
    }
}