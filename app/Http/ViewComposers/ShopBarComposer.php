<?php
/**
 * Created by PhpStorm.
 * User: wanghenshuai
 * Date: 17/2/17
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
use App\Models\Shop\Cart;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\CategoryController;

class ShopBarComposer
{
    public function __construct()
    {
    }
    public function compose(View $view)
    {
	if(Auth::check()){
		$userId = (int) Auth::id();
		$carts = Cart::where('customer_id',$userId)->get();
		$totalCnt = (int) count($carts);
		/* foreach($carts as $key=>$value){ */
		/* 	$tmpCnt = (int) $value->quantity; */
		/* 	$totalCnt += $tmpCnt; */
		/* } */

		if($totalCnt > 0){
			$view->with('cartCnt', $totalCnt);
		}
	}
    }
}
