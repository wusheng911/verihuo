<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiClient;
use App\Models\Node;
use App\Models\Shop\Product;
use App\Models\Shop\Merchant;
use App\Services\Helpers;
use DB;
use Ord;
use Log;
use Illuminate\Support\Facades\Session;


class ShopController extends Controller
{
    public function __construct(Request $request)
    {
        $request->session()->put("platform", "shop");
    }
    
    /**
     * 
     * @return type
     */
    public function index(Request $request) 
    {
        $message_type = config('constants.PROMPT_INFO');
        $message = '';
        
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }        
      
        $adpositions = [];
        $api_client = new ApiClient();
        if ($api_client->get('/api/adpositions?adcode=Mobile|Shop|Home|')) {
            $adpositions = $api_client->getBody();
        }     
        $nodes = Node::adPositionsToNodes($adpositions);  
             
        return view('mobile.shop.index', compact('nodes', 'adpositions', 'message_type', 'message'));        
    }
    
    /***
     * 商品分类展示页
     */
    public function listProducts(Request $request, $id)
    {      
        //get category info
        $category = [];
        $api_client = new ApiClient();
        //if id is a prent category then it contains all child categoires in $category["child"]
        if ($api_client->get("/api/shopcategories/$id")) {
            $category = $api_client->getBody();
        }
                                   
        $sort = $request->has("sort")?$request->input("sort"):"show_price";     
        $order= $request->has("order")?$request->input("order"):"asc";
        $products = [];

        if ($category) {
            $ids = [];
            
            array_push($ids, $id);
            
            if (isset($category["child"]) && count($category["child"]) > 0) {
                foreach($category["child"] as $child) {
                    array_push($ids, $child["id"]);
                }
            }
            
            $products = Product::select(['id','shop_category_id','name',
                'sn','info','area','package','show_price','show_min_price','sale_quantity',
                'show_max_price','status','is_available','created_at','updated_at'])
                ->with("images")
                ->whereIn("shop_category_id", $ids)
                ->orderBy(strtolower($sort), strtolower($order))
                ->paginate(config('chaohun.product_list_count'));
        }
        
        return view('mobile.shop.list', ['category' => isset($category)?$category:[], 
            'products' => ($products)?$products:[], 
            'sort' => $sort,
            'order' => $order]);
    }
    
    /***
     * 商品详情页
     * 
     */
    public function showProduct(Request $request, $id) 
    {
        $message_type = config('constants.PROMPT_INFO');
        $message = '';
        
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }        
        
        if (empty($id)) {
            $message_type = config('constants.PROMPT_WARNING');
            $message = '请选择一件商品！';
        }
      
        $product=$product_skus=$skus=$attributes=$category=$carts=null;
        $sku_count = 0;
        $api_client = new ApiClient();
        if ($api_client->get('/api/products/' . $id)) {
            $product = $api_client->getBody();   
            
            $api_client = new ApiClient();
            if ($api_client->get("/api/skus?product_id=$id")) {
                $product_skus = $api_client->getBody();
                if ($product_skus['skus']) {
                    foreach($product_skus['skus'] as $sku) {
                        $sku_count = $sku_count + intval($sku["quantity"]);
                    }
                }
                $skus=$product_skus['skus'];
                $shop_attrs = $product_skus['shop_attributes'];
            }
            
            $api_client = new ApiClient();
            if ($api_client->get("/api/shopcategories/{$product['shop_category_id']}")) {
                $category = $api_client->getBody();
            }            
        } else {
            $message_type = config('constants.PROMPT_WARNING');
            $message = '查看的商品不存在！';
            $product = null;
        }
        
        return view('mobile.shop.product', compact('product', 'skus', 'sku_count', 
            'shop_attrs', 'category', 'message_type', 'message'));         
    }
  
    
    public function showCart(Request $request) 
    {
        $message_type = config('constants.PROMPT_INFO');
        $message = '';
        $carts = $cart_skus = null;
        
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }        
                   
        $user = Helpers::getCurrentUser();

        if (!empty($user)) {
            $api_client = new ApiClient();
            if ($api_client->get("/api/carts?user_id={$user['id']}")) {
                $carts = $api_client->getBody();
            }
        } else {
            $message_type = config('constants.PROMPT_WARNING');
            $message = "您还没有登录！";
        }
       	$merchants = array();         
        if (isset($carts) && count($carts) > 0) {
            foreach($carts as &$cart) {
                if ($cart["sku_id"] > 0) {
                    $sku = $this->getDisplayAttributesBySku($cart["sku_id"]);
		    if(!array_key_exists($cart['merchant_id'],$merchants)){
			    $merchants[$cart['merchant_id']] = array();
			    $merchants[$cart['merchant_id']]['info'] = array();
			    $info = Merchant::find($cart['merchant_id']);
			    if(!empty($info)){
				$merchants[$cart['merchant_id']]['info'] = $info;
			    }
			    $merchants[$cart['merchant_id']]['carts'] = array();
		    }
                    $cart["sku_price"] = $sku["sku_price"];
                    $cart["sku_attrs"] = $sku["sku_attrs"];
		    $merchants[$cart['merchant_id']]['carts'][] = $cart; 
                }
            }             
            $request->session()->put("Customer.cart_quantity", $carts[0]["total_quantity"]);            
        } else {
            $request->session()->put("Customer.cart_quantity", 0);
        }            
        return view('mobile.shop.cart', compact('carts','message_type', 'message','merchants'));         
    }
    
    public function showInvoice(Request $request)
    {
        $message_type = config('constants.PROMPT_INFO');
        $message = ''; 
        
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        } 
        
        $user = Helpers::getCurrentUser();
        
        $invoices = [];

        if (!empty($user)) {
            $api_client = new ApiClient();
            if ($api_client->get("/api/invoices?user_id={$user['id']}")) {
                $invoices = $api_client->getBody();
            }
        } else {
            $message_type = config('constants.PROMPT_WARNING');
            $message = "您还没有登录！";
        }
                
        if (isset($invoices) && count($invoices) > 0) {
            $invoices = array_filter($invoices, function($item) {
                return $item["invoice_title_type"] == 2;
            });
        }            

        return view('mobile.shop.invoice', compact('invoices','message_type', 'message'));         
    }    
    
    /*************************
     * 开始下单, URL中可以添加1个参数 
     * cart_ids: 新建一个订单时，提供购物车中需要加入订单的条目的Id
     */
    public function startOrder(Request $request) 
    {
        $message_type = config('constants.PROMPT_INFO');
        $message = '';
                
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }
        
        if (Helpers::isUserLoggedIn() === false) {
            return redirect('/shop/cart')->with('promptMessage', [
                'message_type' => config('constants.PROMPT_WARNING'), 
                'message' => '您还没有登录！'
            ]);
        }      
        
        $user = Helpers::getCurrentUser();
        
        $order = null;
        if ($request->isMethod("POST")) {
            $cart_ids = $request->has("cart_ids")?explode(",", $request->input("cart_ids")):[];           
            if (empty($cart_ids)) {
                return response()->json(['error' => '您还没有选择购物车中的商品！'], 500);               
            } else {            
                $order = $this->getFreshOrderByCarts($user["id"], $cart_ids);
                if (!empty($order["merchants"])) {
                    foreach($order["merchants"] as $merchant) {
                        if (isset($merchant["carts"]) && is_array($merchant["carts"])) {
                            foreach($merchant["carts"] as $cart) {
                                if ($cart["is_available_product"] != 1) {
                                    return response()->json(['error' => "{$cart["product_name"]} 已下架!"], 500);
                                }
                                if ($cart["product_status"] != 2) {
                                    return response()->json(['error' => "{$cart["product_name"]} 还没有通过审核!"], 500);
                                }
                                if ($cart["sku_quantity"] == 0) {
                                    return response()->json(['error' => "{$cart["product_name"]} 目前缺货!"], 500);
                                }
                                if ($cart["quantity"] > $cart["sku_quantity"]) {
                                    return response()->json(['error' => "{$cart["product_name"]} 数量超出了出货量!"], 500);
                                }
                            }
                        } 
                    }
                    
                    $request->session()->forget("Order");
                    $request->session()->put("Order", $order);
                    $merchants = isset($order["merchants"])?$order["merchants"]:null;
                    $address_id = isset($order["address_id"])?$order["address_id"]:null;
                } else {
                    return response()->json(['error' => '找不到您选择的商品，无法下单！'], 500);                                
                }  
            }           
        } else {
            if ($request->session()->has("Order")) {
                $order = $request->session()->get("Order");               
            } else {
                return redirect('/shop/cart')->with('promptMessage', [
                    'message_type' => config('constants.PROMPT_WARNING'), 
                    'message' => '请您重新选择商品后下单！'
                ]);                
            }    
        }
        
        $merchants = isset($order["merchants"])?$order["merchants"]:null;
        $total_price = isset($order["total_price"])?$order["total_price"]:null;
        if (isset($order["address_id"])) {
            $address_id = is_array($order["address_id"])?array_shift($order["address_id"]):$order["address_id"];
        } else 
            $address_id = null; 
        $address = [];
                    
        $api_client = new ApiClient();            
        if (!empty($address_id)) {
            if ($api_client->get("/api/addresses/{$address_id}")) {
                $address = $api_client->getBody();
            }            
        } else {             
            if ($api_client->get("/api/addresses?user_id={$user['id']}&default_only=1")) {
                $addresses = $api_client->getBody();
                $address = $addresses?array_shift($addresses):[];
            }                 
        }   
        
        
        //return $merchants;
        return view('mobile.shop.order', compact('merchants', 'address', 'total_price', 'message_type', 'message'));        
    }  
    
    /***
     * The POST call to create an order
     */
    public function createOrder(Request $request)
    {        
        if (Helpers::isUserLoggedIn() === false) {
            Log::error("Create order failure: user is not log in!");
            return response()->json(['error' => '您还没有登录!'], 500);            
        }      
        
        $user = Helpers::getCurrentUser();        
        
        if (!$request->session()->has("Order")) {
            Log::error("Create order failure: order does not exist!");
            return response()->json(['error' => '请您重新选择商品后下单!'], 500);              
        }
        
        $order = $request->session()->get("Order");
        $merchants = isset($order["merchants"])?$order["merchants"]:null;
        $total_price = isset($order["total_price"])?$order["total_price"]:null;
        //$pay_id = isset($order["pay_id"])?$order["pay_id"]:3;
        
        if (isset($order["address_id"])) {
            $address_id = is_array($order["address_id"])?array_shift($order["address_id"]):$order["address_id"];
        } else 
            $address_id = null;        
        
        if (empty($merchants) || empty($total_price)) {
            Log::error("Create order failure: merchants does not exist!");
            return response()->json(['error' => '请您重新选择商品后下单!'], 500);             
        } 
        
        if (empty($address_id)) {
            Log::error("Create order failure: address does not exist!");
            return response()->json(['error' => '请您选择收件地址!'], 500);  
        }
        
        if ($request->isMethod("POST")) {            
            $data = (object) $request->json()->all();
            $remarks = isset($data->remarks)?$data->remarks:[];
            
            $order_ids = [];
            foreach($merchants as $key => $merchant) {
                if (isset($merchant["carts"]) && count($merchant["carts"]) > 0) {
                    $skus = $cart_ids = [];
                    
                    foreach($merchant["carts"] as $cart) {
                        array_push($skus, ["sku_id" => $cart["sku_id"],
                            "quantity" => $cart["quantity"]]);
                        array_push($cart_ids, $cart["id"]);
                    }
                    
                    $str_remark = "";
                    foreach($remarks as $remark) {
                        if (count($remark) > 0) {
                            if (isset($remark["merchant_id"]) && $remark["merchant_id"] == $key) {
                                $str_remark = isset($remark["remark"])?trim($remark["remark"]):"";
                                break;
                            }
                        }
                    }
                    
                    Log::info("remarks " . PHP_EOL . print_r($remarks, true));
                    Log::info("createOrder():: remark: {$str_remark}, address_id: {$address_id}, skus: " . PHP_EOL . print_r($skus, true));
                    
                    $order = Ord::create($skus, $address_id, Helpers::isWechat()?5:3, $str_remark);
                    if ($order) {
                        array_push($order_ids, $order->id);
                        $this->deleteCartItems($cart_ids);
                    } else {
                        Log::error("Create order failure: Ord:create failed!");
                        return response()->json(['error' => '出错啦,请联系在线客服!'], 500);
                    } 
                }
            }
            
            if (!empty($order_ids)) {
                $request->session()->forget("Order");
            }           
            
            return ["order_ids" => $order_ids,
                "cart_quantity" => $request->session()->get("Customer.cart_quantity")];
            
        } else {
            Log::error("Create order failure: request method is not post!");
            return response()->json(['error' => '出错啦,请联系在线客服!'], 500);  
        }
    }
        
    /*************************************
     * 根据sku_id（货号）获得销售属性的内容数组
     * 
     * @param $sku_id string sku id 
     * @return array 返回货号对应的销售属性的数组
     */
    public function getDisplayAttributesBySku($sku_id) 
    {
        $skus = $sku_attrs = [];
        
        $result = [
            "sku_price" => '',
            "sku_attrs" => [],
            "product_id" => '',
            "merchant_id" => '0',
            "sku_quantity" => '',
        ];
        
        $api_client = new ApiClient();
        if ($api_client->get("/api/skus?sku_id={$sku_id}&is_sell=1")) {
            $skus = $api_client->getBody();
        }   
        
        if (count($skus) > 0) {
            $sku = array_shift($skus["skus"]);
            if (!empty($sku)) {
                $result["sku_price"] = $sku["price"];
                $result["product_id"] = $sku["product_id"];
                $result["merchant_id"] = $sku["merchant_id"];
            }
            
            $sku_attrs = $skus["shop_attributes"];
            
            if (count($sku_attrs) > 0) {
                foreach($sku_attrs as $sku_attr) {
                    if (isset($sku_attr["shop_attribute_values"]) 
                        && count($sku_attr["shop_attribute_values"]) > 0) {
                        $attr_value = array_shift($sku_attr["shop_attribute_values"]);
                        array_push($result["sku_attrs"], ["name" => $sku_attr["shop_attribute_showname"],
                        "value" => $attr_value["value"]]);
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function getFreshOrderByCarts($user_id, array $cart_ids = [])
    {
        $str_cart_ids = empty($cart_ids)?'':implode(",", $cart_ids);
        $carts = $merchants = $merchant_ids = [];
        $total_price = 0;
        

        $api_client = new ApiClient();
        if ($api_client->get("/api/carts?user_id={$user_id}" . (empty($str_cart_ids)?'':"&ids={$str_cart_ids}"))) {
            $carts = $api_client->getBody();
        }

        if (isset($carts) && count($carts) > 0) {
            foreach($carts as &$cart) {
                if ($cart["sku_id"] > 0) {
                    $sku = $this->getDisplayAttributesBySku($cart["sku_id"]);
                    $cart["sku_price"] = $sku["sku_price"];
                    $cart["sku_attrs"] = $sku["sku_attrs"];
                    $cart["product_id"] = $sku["product_id"];
                    $cart["merchant_id"] = $sku["merchant_id"];
                    array_push($merchant_ids, $sku["merchant_id"]);
                }
            }   

            $merchant_ids = array_unique($merchant_ids);
            //如果没有商家记录, 页面展示时,应该用$carts
            if (count($merchant_ids) <=1 && $merchant_ids[0] == "0") {
                $quantity_totalprice = $this->getQuantityTotalPriceByCart($carts);               
                $merchants["0"] = [
                    "carts" => $carts,
                    "quantity" => $quantity_totalprice["quantity"],
                    "total_price" => $quantity_totalprice["total_price"]
                ];
            } else { //如果有商家记录, 页面展示时, 应该用$merchants
                $str_merchants = implode(",", $merchant_ids);
                $api_client = new ApiClient();
                if ($api_client->get("/api/merchants?ids={$str_merchants}"))  {
                    $temp_merchants = $api_client->getBody();

                    foreach($temp_merchants as &$merchant) {
                        $merchant["carts"] = array_filter($carts, function($item) use ($merchant) { 
                            return $item["merchant_id"] == $merchant["id"];   
                        });
                        //计算这个商家下的商品数量和价格合计
                        if ($merchant["carts"]) {
                            $quantity_totalprice = $this->getQuantityTotalPriceByCart($merchant["carts"]);
                            $merchant["quantity"] = $quantity_totalprice["quantity"];
                            $merchant["total_price"] = $quantity_totalprice["total_price"];
                        } else {
                            $merchant["carts"] = [];
                        }
                        $merchants[$merchant["id"]] = $merchant;  
                    }
                } 
            }
        }  
        
        //计算total price
        foreach($merchants as $merchant) {
            $total_price = $total_price + $merchant["total_price"];
        }
        
        //获得默认收货地址
        $api_client = new ApiClient();            
        $address = [];
        
        if ($api_client->get("/api/addresses?user_id={$user_id}&default_only=1")) {
            $addresses = $api_client->getBody();
            $address = $addresses?array_shift($addresses):[];
        }
        
        return [
            "merchants" => $merchants,
            "total_price" => $total_price,
            "address_id" => empty($address)?'':$address['id']
        ];
    }
        
    private function getQuantityTotalPriceByCart($carts)
    {
        $result = [
            "quantity" => 0,
            "total_price" => 0
        ];
        
        if (isset($carts) && is_array($carts)) {
            foreach($carts as $cart) {
                $result["quantity"] += $cart["quantity"];
                $result["total_price"] += $cart["quantity"] * $cart["sku_price"];
            }
        }
        
        return $result;
    }
    
    private function deleteCartItems(array $cart_ids)
    {
        $ids = implode(",", $cart_ids);
        $total_quantity = Session::get("Customer.cart_quantity");
        $api_client = new ApiClient();

        if (!$api_client->delete("/api/carts/" . $ids)) {
            Log::error("Delete cart item failed with API Request: " . $api_client->getUrl());
        } else {
            $delete_count = $api_client->getBody();            
            //更新session中的购物车货品数量
            $total_quantity = $total_quantity - $delete_count[0];
            Session::put("Customer.cart_quantity", ($total_quantity >=0)?$total_quantity:0);
        }    
        
        return $total_quantity;        
    }
    

}
