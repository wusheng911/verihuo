<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;

use Logis;
use JavaScript;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Helpers;
use App\Services\ApiClient;
use App\Customer;
use App\Models\Node;
use App\Models\Shop\Product;
use App\Models\Shop\Sku;
use App\Models\Shop\Category;

class CategoryController extends Controller
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
    public function index(Request $request, $id)
    {
        $api_clientes = new apiclient();
        $shopCategoryTops = array();
        $shopCategoryTopsNodes = array(); 
        if($api_clientes->get('/api/adpositions?adcode=PC|ShopCategory|A')){
            $shopCategoryTops = $api_clientes->getBody();
            if(!empty($shopCategoryTops)){
                $shopCategoryTopsNodes= Node::adPositionsToNodes($shopCategoryTops);
            }
        }
        $perpage = 15;
        $order = $request->input('order');
        $orderby = $request->input('orderby');
        if(!in_array($order, ['desc', 'asc'])){
            $order = 'desc';
        };
        if(!in_array($orderby, ['sale_quantity', 'show_price'])){
            $orderby = 'sale_quantity';
        };
        $category = Category::find((int) $id);
        $cids = [$category->id];
        if($category->children){
            $cids = array_merge($cids, $category->children->reduce(function($c, $a){
                $rc = $c;
                if($a->children){
                    $rc = array_merge($c, $a->children->reduce(function($c, $a){
                        return array_merge($c, [$a->id]);
                    }, []));
                }
                return array_merge($rc, [$a->id]);
            }, []));
        }
        $products = Product::where([['is_available', 1], ['status', 2]])
                  ->whereIn('shop_category_id', $cids)
                  ->orderBy('updated_at', 'desc')
                  ->orderBy($orderby, $order)
                  ->paginate($perpage);

        return view('pc.shop.category.list',
                    ['products' => $products, 'category' => $category,
                     'orderby' => $orderby, 'order' => $order,'shopCategoryTops'=>$shopCategoryTops,'nodes'=>$shopCategoryTopsNodes]);
    }

    public function search()
    {

    }

}
