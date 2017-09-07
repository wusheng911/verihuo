<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use DB;
use App\Services\ApiResponse;
use App\Models\Zone;
use App\Models\Address;
use App\Customer;
use Exception;


class AddressController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {       
        try {
            $customer_id = $request->has('user_id')?$request->input('user_id'):null;
            $default_only = $request->has('default_only')?$request->input('default_only'):"0";
            
            if (empty($customer_id)) {
                throw new Exception("用户不存在！");
            }
            
            $query_builder = Address::where("customer_id", "=", $customer_id);
            
            if ($default_only == "1") {
                $query_builder = $query_builder->where("is_default", "=", 1);
            }
            
            $addresses = $query_builder->orderBy("is_default", "desc")
                ->orderBy("created_at", "asc")
                ->get();           
            
            foreach($addresses as &$address) {
                $address->full_path = $this->getAddressFullPath($address);
            }
            
            $response = ApiResponse::get(false, $addresses->toArray());           
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
     * Example: curl -X POST -d 'zone_id=383&user_id=15&consignee_name=max&consignee_phone=16324256347
     * &details=%E4%B8%AD%E5%B1%B1%E4%B8%9C%E8%B7%AF111%E5%8F%B7&is_default=0' http://localhost/api/addresses
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $zone_id = $request->has("zone_id")?$request->input("zone_id"):null;
            $customer_id = $request->has("user_id")?$request->input("user_id"):null;
            $consignee_phone = $request->has("consignee_phone")?intval($request->input("consignee_phone")):null;
            $consignee_name = $request->has("consignee_name")?$request->input("consignee_name"):"";
            $details = $request->has("details")?$request->input("details"):"";
            $is_default = $request->has("is_default")?$request->input("is_default"):0;
            
            $zone = Zone::find($zone_id);
            $customer = Customer::find($customer_id);
            
            if (!$zone) {
                throw new Exception("找不到相应的地区！");
            }
            
            if (!$customer) {
                throw new Exception("用户不存在！");
            }
            
            if (empty($consignee_name)) {
                throw new Exception("收货人不能为空！");
            }
            
            if (empty($consignee_phone)) {
                throw new Exception("收货人电话不能为空！");
            } 
            
            if (empty($details)) {
                throw new Exception("详细地址不能为空！");
            }             
            
            $default_address = Address::where("is_default", "=", 1)
                    ->where("customer_id", "=", $customer->id)
                    ->first();
            
            $address = new Address;
            $address->zone_id = $zone->id;
            $address->customer_id = $customer->id; 
            $address->consignee_name = $consignee_name;
            $address->consignee_phone = $consignee_phone;
            $address->details = $details;
            $address->is_default = $is_default;
            $address->save();
            
            if ($default_address && ($is_default == "1")) {
                $default_address->is_default = 0;
                $default_address->save();
            }
            
            $response = ApiResponse::get(false, $address->toArray());
          
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
    public function show($id)
    {
        try {
            $address = Address::where("id", "=", $id)->with("zone")->first();
                        
            if ($address == false) {
                $response = ApiResponse::get(true, ['message' => "Address $id Not Found."]);                  
            } else {                    
                $address->full_path = $this->getAddressFullPath($address);
                $response = ApiResponse::get(false, $address->toArray());
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       try {
            $zone_id = $request->has("zone_id")?$request->input("zone_id"):null;
            $customer_id = $request->has("user_id")?$request->input("user_id"):null;
            $consignee_phone = $request->has("consignee_phone")?intval($request->input("consignee_phone")):null;
            $consignee_name = $request->has("consignee_name")?$request->input("consignee_name"):"";
            $details = $request->has("details")?$request->input("details"):"";
            $is_default = $request->has("is_default")?$request->input("is_default"):0;
            
            $zone = Zone::find($zone_id);
                        
            $address = Address::find($id);
            
            if ($address) {                                      
                if ($customer_id != $address->customer_id) {
                    throw new Exception("无法修改其他用户的地址！");
                } 
                
                //如果已经存在默认地址
                $default_address = Address::where("is_default", "=", 1)
                        ->where("customer_id", "=", $address->customer_id)
                        ->first();                
                
                if ($zone_id) {
                    $address->zone_id = $zone->id;
                }
                
                if ($consignee_name) { 
                    $address->consignee_name = $consignee_name;
                }
                
                if ($consignee_phone) {
                    $address->consignee_phone = $consignee_phone;
                } 
                
                if ($details) {
                    $address->details = $details;
                }
                $address->is_default = $is_default;
                $address->save();
                
                if ($default_address && ($is_default == "1") && ($default_address->id != $address->id)) {
                    $default_address->is_default = 0;
                    $default_address->save();
                }  
            }
            
            $response = ApiResponse::get(false, $address->toArray());
          
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
                Address::wherein("id", $ids)->delete();
            }
                               
            $response = ApiResponse::get(false, $ids);           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);            
        }
        
        return $response;
    }
    
    public function getZones(Request $request)
    {
        $type = $request->has("type")?$request->input("type"):null;
        $parent_id = $request->has("parent_id")?$request->input("parent_id"):null;
       
        $result = []; 
        try {
            switch (strtolower($type)) {
                case "country":
                    $result = DB::table("zones")->select("id as zone_id", "country_id as id", "country as name")->distinct()->get();
                    break;
                case "province":
                    $parent_id = empty($parent_id)?1:$parent_id;
                    $result = DB::table("zones")->where("country_id", "=", $parent_id)
                        ->where("province_id", "<>", null)
                        ->where("province", "<>", "")
                        ->select("province_id as id", "province as name")->distinct()->get();
                    break;
                case "city":
                    if ($parent_id) {
                        $result = DB::table("zones")->where("province_id", "=", $parent_id)
                            ->where("city_id", "<>", null)
                            ->where("city", "<>", "")
                            ->select("city_id as id", "city as name")->distinct()->get();
                    }
                    break;
                case "district":
                    if ($parent_id) {
                        $result = DB::table("zones")->where("city_id", "=", $parent_id)
                            ->where("district_id", "<>", null)
                            ->where("district", "<>", "")
                            ->select("id as zone_id", "district_id as id", "district as name")->distinct()->get();
                    }
                    break;
                default:
                    ;
            }
            
            $response = ApiResponse::get(false, $result);           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);            
        } 
               
        return $response;        
 
    }
    
    /***
     * 从一个address model对象获得完整地址的字符串
     * 
     */
    private function getAddressFullPath(Address $address)
    {
        $result = "";
        
        if (isset($address["zone"])) {
            $result = $address["zone"]["province"] . $address["zone"]["city"] . $address["zone"]["district"];
        }
        
        return $result;
    }
}
