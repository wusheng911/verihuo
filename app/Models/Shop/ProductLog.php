<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    protected $fillable = [];

    public function nextVersionId(){
        return (int) $this->version + 1;
    }
}