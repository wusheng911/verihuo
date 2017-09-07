<?php
/**
 * Created by PhpStorm.
 * User: wanghenshuai
 * Date: 17/1/16
 * Time: 下午5:45
 */
namespace App\Http\ViewComposers;
use App\Models\Content;
use App\Models\Shop\Category;
use App\Services\ApiClient;
use App\Models\ContentTag;
use Illuminate\View\View;
use App\Models\AdPosition;
use App\Models\ContentCategory;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\CategoryController;

class ShopNavComposer
{
    public function __construct()
    {
    }
    public function compose(View $view)
    {
        $shopCategoryNav = Category::where('level',0)->get();
        $view->with('shopCategoryNav', $shopCategoryNav);
    }
}