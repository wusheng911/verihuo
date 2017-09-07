<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'shop_categories';

    /**
     * 商品分类属性
     */
    public function attributes()
    {
        return $this->hasMany('App\Models\Shop\Attribute', 'shop_category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Shop\Category', 'pid', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Shop\Category', 'pid', 'id');
    }

    public function siblingsAndI()
    {
        return $this->parent->children();
    }
}
