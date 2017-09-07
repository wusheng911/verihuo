<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;

use Logis;
use JavaScript;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Helpers;
use App\Customer;
use App\Models\Shop\Product;
use App\Models\Shop\Sku;

class ProductController extends Controller
{
    /**
     */
    public function isLogin()
    {
        if(!Helpers::getCurrentUser()) {
            return redirect()->to(action('CustomerController@login'));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

    }

    public function search(Request $request, $key)
    {
        $perpage = 15;
        $order = $request->input('order');
        $orderby = $request->input('orderby');
        if(!in_array($order, ['desc', 'asc'])){
            $order = 'desc';
        };
        if(!in_array($orderby, ['sale_quantity', 'show_price'])){
            $orderby = 'sale_quantity';
        };
        $products = Product::where([['is_available', 1], ['status', 2]])
                  ->where(function($q)use($key){
                      $q->where('name', 'like', "%$key%")
                          ->orWhere('info', 'like', "%$key%")
                          ->orWhere('html_info', 'like', "%$key%");
                  })
                  ->orderBy($orderby, $order)->paginate($perpage);

        return view('pc.shop.category.list',
                    ['products' => $products, 'orderby' => $orderby,
                     'order' => $order, 'key' => $key]);

    }

}