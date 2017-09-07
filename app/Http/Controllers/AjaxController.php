<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Shop\Cart;
use Illuminate\Support\Facades\Auth;
use Log;
use Illuminate\Http\Request;
use App\Services\ApiClient;
use App\Models\Zone;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{    
    /**
     * Verify if the privided $value is a registered customer by either: mobile_number, email, user_name
     * 
     * @param Request $request
     * @param string $value could be mobile_number, email, user_name
     * @return string empty if not registered, user_name, phone, email
     */
    public function registeredBy(Request $request, $value) 
    {
        $result = "";
        
        $api_client = new ApiClient();
        if ($api_client->get("/api/customers/registeredby/$value")) {
            $result = $api_client->getBody();
        } else {
            Log::error("AJAX get customer registeredBy with request: " . $api_client->getUrl());
            return response()->json(['error' => '获取不到信息!'], 500);
        }

        return $result;
    }
    
    public function sendSMS(Request $request) 
    {
        $options = [];
        
        if ($request->has('mobile_number')) {
            $options['mobile_number'] = $request->input('mobile_number');
        }      
                
        $api_client = new ApiClient();
        
        $sms_code = [];
      
        if (!$api_client->post('/api/customers/sendsms', $options)) {
            $request->session()->put('smsCode', ['mobile_number' => $request->input('mobile_number'), 'mobile_code' => '1111']);
            Log::error("Send SMS failed with API Request: " . $api_client->getUrl());
            return response()->json(['error' => '发送短信失败!'], 500);
        } else {
            $sms_code = $api_client->getBody();
            $request->session()->put('smsCode', $sms_code);
        }
        
        return $sms_code;     
    }
    

    /**
    * 加入购物车
    * example: curl -X POST -d 'sku_id=2&user_id=1&quantity=1' http://localhost/m/ajax/addtocart
    * 
    * @return array cart record that was just added
    * 
    */  
    public function addToCart(Request $request) {
        $cart = [];
      
        $options["sku_id"] = $request->has('sku_id')?$request->input('sku_id'):null;        
        $options["user_id"] = $request->has('user_id')?$request->input('user_id'):null;
        $options["quantity"] = $request->has('quantity')?$request->input('quantity'):null;
        $options["product_name"] = $request->has("product_name")?$request->input("product_name"):"";
        $options["product_image"] = $request->has("product_image")?$request->input("product_image"):"";

        $api_client = new ApiClient();
        if (!$api_client->get("/api/skus/{$options["sku_id"]}")) {
            Log::error("Add to cart failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => '无法查询到有效的货品!'], 500);
        } else {
            $sku = $api_client->getBody();            
            if ($sku["quantity"] <= 0) {
                return response()->json(['error' => '这个商品缺货！'], 500);
            }
        }
                  
        $api_client = new ApiClient();
        if (!$api_client->post("/api/carts", $options)) {
            Log::error("Add to cart failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => '添加购物车失败!'], 500);
        } else {
            $cart = $api_client->getBody();
            
            //更新session中的购物车货品数量
            if (isset($cart) && is_array($cart) && count($cart) > 0) {
                $request->session()->put("Customer.cart_quantity", $cart[0]["total_quantity"]);
            } else {
                $request->session()->put("Customer.cart_quantity", 0);
            }            
        }             
        return $cart;
    }
    
    /**
     * 判断购物车中的商品是否存在，存在返回“1”, 不存在返回“0”, 部分存在返回“2”
     * 
     * @param Request $request
     * @param type $ids
     * 
     * @return string 存在返回“1”, 不存在返回“0”, 部分存在返回“2”
     */
    public function isCartExist(Request $request)
    {         
        $ids = $request->has('ids')?$request->input('ids'):'';
        $user_id = $request->has('user_id')?$request->input('user_id'):'';        
        
        
        if (!empty($ids) && !empty($user_id)) {
            $api_client = new ApiClient();

            if (!$api_client->get("/api/carts?ids={$ids}&user_id={$user_id}")) {
                Log::error("check cart exist failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
                return response()->json(['error' => '验证购物车状态失败!'], 500);
            } else {
                $carts = $api_client->getBody();
                
                if (!empty($carts)) {
                    foreach(explode(",", $ids) as $id) {
                        $cart_exist = false;                        
                        foreach($carts as $cart) {
                            if ($cart["id"] == $id) {
                                $cart_exist = true;
                            }
                        }
                        if ($cart_exist === false) {
                            return "2";
                        }                       
                    }
                    
                    return "1";
                }                
            } 
        }
        
        return "0";        
    }     

    /**
     * 添加新地址
     * Example: curl -X POST -d 'zone_id=383&user_id=15&consignee_name=max&consignee_phone=16324256347
     * &details=%E4%B8%AD%E5%B1%B1%E4%B8%9C%E8%B7%AF111%E5%8F%B7&is_default=0' http://localhost/m/ajax/addaddress
     * 
     * @param string $id article id
     * @return string HTML for an article comment
     * 
     */
    public function addAddress(Request $request) 
    {        
        $options = [];        
        $options['zone_id'] = $request->has('zone_id')?$request->input('zone_id'):null;
        $options['user_id'] = $request->has('user_id')?$request->input('user_id'):null;
        $options['consignee_name'] = $request->has('consignee_name')?$request->input('consignee_name'):"";
        $options['consignee_phone'] = $request->has('consignee_phone')?$request->input('consignee_phone'):"";
        $options['details'] = $request->has('details')?$request->input('details'):"";
        $options['is_default'] = $request->has('is_default')?$request->input('is_default'):0;
        
        $api_client = new ApiClient();
        $address = [];
        $address_id = 0;
      
        if (!$api_client->post('/api/addresses', $options)) {
            Log::error("Create Comment failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => '增加地址失败!'], 500);
        } else {
            $address = $api_client->getBody();
            $address_id = $address["id"];
            
            $api_client = new ApiClient();
            $addresses = [];
            if ($api_client->get('/api/addresses?user_id=' . $options['user_id'])) {
                $addresses = $api_client->getBody();                             
            } else {
                return response()->json(['error' => '获取地址失败!'], 500);
            }    
        }
        
        return view('pc.elements.addressList', compact('addresses', 'address_id'));        
    } 
    
    /**
     * 更新地址
     * Example: curl -X POST -d 'zone_id=2&user_id=1&consignee_name=abc&
     * consignee_phone=123&details=xyz&is_default=1' http://localhost/m/ajax/updateaddress/$id
     * 
     * @param string $id article id
     * @return string HTML for an article comment
     * 
     */
    public function updateAddress(Request $request, $id) 
    {        
        $options = [];        
        $options['zone_id'] = $request->has('zone_id')?$request->input('zone_id'):null;
        $options['user_id'] = $request->has('user_id')?$request->input('user_id'):null;
        $options['consignee_name'] = $request->has('consignee_name')?$request->input('consignee_name'):"";
        $options['consignee_phone'] = $request->has('consignee_phone')?$request->input('consignee_phone'):"";
        $options['details'] = $request->has('details')?$request->input('details'):"";
        $options['is_default'] = $request->has('is_default')?$request->input('is_default'):0;
        
        
        $address = [];
        $address_id = 0;
        
        $api_client = new ApiClient();
      
        if (!$api_client->put("/api/addresses/{$id}", $options)) {
            Log::error("Create Comment failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => '增加地址失败!'], 500);
        } else {
            $address = $api_client->getBody();
            $address_id = $address["id"];
            
            $api_client = new ApiClient();
            $addresses = [];
            if ($api_client->get('/api/addresses?user_id=' . $options['user_id'])) {
                $addresses = $api_client->getBody();                             
            } else {
                return response()->json(['error' => '获取地址失败!'], 500);
            }   
        }
        
        return view('pc.elements.addressList', compact('addresses', 'address_id'));          
    }  
    
    /***
     * 删除地址
     * 
     * example: curl -X DELETE http://localhost/m/ajax/deleteaddresses/1,2,3
     */
    public function deleteAddresses(Request $request, $ids)
    {   
        $addresses = [];
        $address_id = 0;
        
        $api_client = new ApiClient();

        if (!$api_client->delete("/api/addresses/" . $ids)) {
            Log::error("Add to cart failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => '删除地址失败!'], 500);
        } else {
            $delete_ids = $api_client->getBody();            
        }    
        
        return $delete_ids;
    }    
    

    /**
     * PC端用户中心获取地区
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getZonesForPC(Request $request) {
        $zone_id = $request->has('zone_id') ? $request->input('zone_id') : "";
        if($zone_id != '') {
            $customerZone = Zone::where('id',$zone_id)->first();
            $customerZoneLevel = $customerZone->level;
        } else {
            $customerZone = '';
            $customerZoneLevel = '';
        }
        $zones = [];
        $zones['province'] = Zone::where('level',1)->get();
        $zones['city'] = Zone::where('level',2)->whereNotNull('city')->get();
        $zones['district'] = Zone::where('level',3)->whereNotNull('district')->get();
        $provinceResult = $cityResult = $districtResult = '';

        foreach($zones['province'] as $zone) {
            if($customerZone != '') {
                if($customerZone->province_id == $zone->province_id) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
            } else {
                $selected = '';
            }
            $provinceResult .= "<option data-id=\"{$zone->id}\" data-type='province' data-zoneid=\"{$zone->province_id}\" value=\"{$zone->id}\" $selected>{$zone->province}</option>";
        }

        foreach($zones['city'] as $zone) {
            if($customerZone != '') {
                if($customerZone->city_id == $zone->city_id) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
            } else {
                $selected = '';
            }
            $cityResult .= "<option data-id=\"{$zone->id}\" data-type='city' data-zoneid=\"{$zone->city_id}\" class=\"{$zone->pid}\" value=\"{$zone->id}\" $selected>{$zone->city}</option>";
        }

        foreach($zones['district'] as $zone) {
            if($customerZone != '') {
                if($customerZone->district_id == $zone->district_id) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }
            } else {
                $selected = '';
            }
            $districtResult .= "<option data-id=\"{$zone->id}\" data-type='district' data-zoneid=\"{$zone->district_id}\" class=\"{$zone->pid}\" value=\"{$zone->id}\" $selected>{$zone->district}</option>";
        }
        $result = array('province'=>$provinceResult,'city'=>$cityResult,'district'=>$districtResult,'customerZoneLevel'=>$customerZoneLevel);
        //dd($result['province']);
        return response()->json($result);
    }

    /**
     * 删除收货地址
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAddress(Request $request){
        $id = (int)($request->has('address_id') ? $request->input('address_id') : "");
        Address::where('id',$id)->forceDelete();
        return response()->json(['success']);
    }

    /*
    public function getZonesForPc1() {
        $result = [];
        $provinceResult = $cityResult = $districtResult = '';
        $zones['province'] = Zone::where('level',1)->get();
        foreach($zones['province'] as $zone) {
            $result['10000'][$zone->province_id] = $zone->province;
        }
        foreach ( $zones['province'] as $zone1) {
            $cities = Zone::where('level',2)
                ->where('province_id',$zone1->province_id)
                ->whereNotNull('city')
                ->get();
            foreach ($cities as $city) {
                $result[$city->province_id][$zone->city_id] = $city->city;
                $districts = Zone::where('level',3)
                    ->where('city_id',$city->city_id)
                    ->whereNotNull('district')
                    ->get();
                foreach ($districts as $district) {
                    $result[$district->city_id][$district->district_id] = $district->district;
                }
            }
        }
        //dd($result);
        return response()->json($result);
    }*/

    public function getZones(Request $request) 
    {
        $province = $request->has('province')?$request->input('province'):null;
        $city = $request->has('city')?$request->input('city'):null;
        
        $cities = $districts = [];
        
        if ($province) {
            $api_client = new ApiClient();
            if ($api_client->get("/api/addresses/getzones?type=city&parent_id={$province}")) {
                $cities = $api_client->getBody();
            } else {
                Log::error("AJAX getzones with request: " . $api_client->getUrl());
                return response()->json(['error' => '获取不到市区信息!'], 500);
            }     
        }
        
        if ($city) {
            $api_client = new ApiClient();
            if ($api_client->get("/api/addresses/getzones?type=district&parent_id={$city}")) {
                $districts = $api_client->getBody();
            } else {
                Log::error("AJAX getzones with request: " . $api_client->getUrl());
                return response()->json(['error' => '获取不到区县信息!'], 500);
            }              
        }
                
        $result = [
            "city" => empty($cities)?[]:$cities,
            'district' => empty($districts)?[]:$districts
        ];
        
        return $result;
    }

    /**
     * 更新购物车产品数量
     *
     * @param Request $request
     * @param $cart_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCartProductQuantity(Request $request,$cart_id){
        $quantity = (int)($request->has('quantity') ? $request->input('quantity') : "");
        $action = $request->input('action');
        $customer_id = Auth::user()->id;
        if($quantity!='' && $customer_id) {
            $result = 1;
        } else {
            $result = 0;
        }
        $cart = Cart::where('id',$cart_id)
                    ->where('customer_id',$customer_id)
                    ->first();
        //购物车所含商品的库存
        $product_quantity = $cart->sku->quantity;
        if($action == 'add') {
            if($quantity<=$product_quantity) {
                Cart::where('id',$cart_id)
                    ->where('customer_id',$customer_id)
                    ->update([
                        'quantity'=>$quantity
                    ]);
            } else {
                $result = 3;
            }
        }

        return  response()->json([$result]);
    }

    /**
     * 删除购物车商品
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCartProduct(Request $request) {
        $cart_id = (int)($request->has('cartId') ? $request->input('cartId') : "");
        $cart_ids = $request->has('cartIds') ? explode(',',$request->input('cartIds')) : '';
        $customer_id = Auth::user()->id;

        if($cart_ids) {
            Cart::where('customer_id',$customer_id)
                ->whereIn('id',$cart_ids)
                ->forceDelete();
            $result = 1;
        } elseif(!empty($cart_id)) {
            Cart::where('id',$cart_id)
                ->where('customer_id',$customer_id)
                ->forceDelete();
            $result = 1;
        } else {
            $result = 0;
        }

        return  response()->json([$result]);
    }
}