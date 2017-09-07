<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    public $timestamps = false;
    protected $fillable = ['product_id','image','pos','is_first','device'];
    //
}
