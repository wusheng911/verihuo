<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Models\Shop\Cart;
use App\Models\Shop\Sku;
use App\Customer;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Exception;
use DB;
use Log;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $customer_id = $request->has('user_id')?$request->input('user_id'):null;
            $ids = $request->has('ids')?explode(",", $request->input('ids')):[];
            
            $carts = $this->getCarts($customer_id, $ids);              
            $response = ApiResponse::get(false, $carts->toArray());           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }
        
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Example: curl -X POST -d 'sku_id=2&user_id=1&quantity=1' http://localhost/api/carts
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $sku_id = $request->has("sku_id")?$request->input("sku_id"):null;
            $customer_id = $request->has("user_id")?$request->input("user_id"):null;
            $quantity = $request->has("quantity")?intval($request->input("quantity")):null;
            $product_name = $request->has("product_name")?$request->input("product_name"):"";
            $product_image = $request->has("product_image")?$request->input("product_image"):"";
            
            $sku = Sku::find($sku_id);
            $customer = Customer::find($customer_id);
            
            if (!$sku) {
                throw new Exception("找不到相应的货品！");
            }
                        
            if (!$customer) {
                throw new Exception("用户不存在！");
            }
            
            if ($quantity <= 0) {
                throw new Exception("数量不能为0！");
            }
            
            $cart = Cart::where("sku_id", "=", $sku->id)
                    ->where("customer_id", "=", $customer->id)
                    ->first();
            
            //如果购物车中已经有相同商品
            if ($cart) {
                $cart->quantity = $cart->quantity + $quantity;
            } else {
                $cart = new Cart;
                
                $cart->sku_id = $sku->id;
                $cart->customer_id = $customer->id; 
                $cart->quantity = $quantity;
                $cart->session_id = 0;
                $cart->merchant_id = $sku->merchant_id;
                $cart->product_id = $sku->product_id;
                $cart->product_name = $product_name;
                $cart->product_info = "";
                $cart->image = $product_image;
                $cart->market_price = 0;
            }
                        
            $cart->save();
            
            //由于设计需要,返回购物车中与当前用户购买的所有货品(包含总数量, 见getCarts)
            $carts = $this->getCarts($customer_id);            
            $response = ApiResponse::get(false, $carts->toArray());
          
        } catch (Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }
        
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {     
        try {
            $cart = Cart::find($id);
                        
            if ($cart == false) {
                $response = ApiResponse::get(true, ['message' => "Cart $id Not Found."]);                  
            } else {                       
                $response = ApiResponse::get(false, $cart->toArray());
            }           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);            
        }
        
        return $response;
    }  

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage. Example:
     * curl -X PUT -d 'quantity=2' http://localhost/api/carts/4
     *
     * @param \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $quantity_diff = 0;
            
            $cart = Cart::find($id);
            
            if ($request->input('quantity')) {
                $quantity_diff = intval($request->input('quantity')) - $cart->quantity;
                $cart->quantity = $request->input('quantity');
            }
            
            $sku = Sku::find($cart->sku_id);
            
            if ($sku) {
                if (($quantity_diff > 0) && (intval($request->input('quantity')) > $sku->quantity)) {
                    throw new Exception("没有更多的货品了！");
                }
            } else {
                throw new Exception("找不到相应的货品！");
            }
                      
            $cart->save();
            $response = ApiResponse::get(false, [$quantity_diff]);
            
        } catch (Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        } 
        
        return $response;        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = (stripos($id, ",") === false)?[$id]:explode(",", $id);
        
        try {
            if (!empty($ids)) {
                $carts = Cart::wherein("id", $ids)->get();
            
                $total_quantity = 0;
                foreach($carts as $cart) {
                    $total_quantity = $total_quantity + $cart->quantity;
                }              
              
                Cart::wherein("id", $ids)->delete();
            }
                               
            $response = ApiResponse::get(false, [$total_quantity]);           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);            
        }
        
        return $response;        
    }   
    
    /**
     * 获取购物车条目
     * 
     * @param integer $customer_id Customer Id 
     * @param array $ids cart ids (Optional)
     * @return Illuminate\Database\Eloquent\Collection 返回一个Laravel的数据集合对象
     */
    private function getCarts($customer_id, array $ids = []) 
    {
        $carts = new Collection();
        
        if ($customer_id > 0) {
            $query_builder = Cart::where("carts.customer_id", "=", $customer_id)
                ->join("products", "carts.product_id", "=", "products.id")
                ->join("skus", "carts.sku_id", "=", "skus.id")
                ->select("carts.id",
                    "carts.customer_id",
                    "carts.session_id",
                    "carts.sku_id",
                    "carts.product_id",
                    "carts.merchant_id",
                    "carts.product_name",
                    "carts.product_info",
                    "carts.quantity",
                    "carts.market_price",
                    "carts.image",
                    "carts.created_at",
                    "carts.updated_at",
                    "products.is_available as is_available_product",
                    "products.status as product_status",
                    "skus.quantity as sku_quantity");
            
            if (count($ids) > 0) {
                $carts = $query_builder->wherein("carts.id", $ids)->get();
            } else {
                $carts = $query_builder->get();
            }
            
            if ($carts->count() > 0) {
                $total_quantity = 0;
                foreach($carts as $cart) {
                    $total_quantity = $total_quantity + intval($cart->quantity);
                }

                foreach($carts as &$cart) {
                    $cart->total_quantity = $total_quantity;
                }
            }
        }
        
        return $carts;
    }
}
