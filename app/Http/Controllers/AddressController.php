<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Exception;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Services\Helpers;
use App\Services\ApiClient;

class AddressController extends Controller
{
    /***
     * 地址列表页
     * 
     */
    public function index(Request $request)
    {
        $message_type = config('constants.PROMPT_INFO');
        $message = '';
        $addresses = [];
        $selected_id = '';
        
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }   
        
        if (Helpers::isUserLoggedIn() === false) {
            $message_type = config('constants.PROMPT_WARNING');
            $message = '您还没有登录！';            
        }
        else {
            /* selected_id用于从下单页选择地址时，传入上次选择的地址id, 
             * 如果上次无选择地址，则传入0表示，从下单页进入的地址列表页，从而与从个人中心进入地址列表页的情况区分
             * 从下单页进入地址列表页后，当选择一个地址条目后，表示选中了这个地址，页面自动跳回下单页
             */           
            $selected_id = $request->has("selected_id")?$request->input("selected_id"):'';
            $customer = Helpers::getCurrentUser();

            $api_client = new ApiClient();
            $addresses = null;
            if ($api_client->get('/api/addresses?user_id=' . $customer["id"])) {
                $addresses = $api_client->getBody();
            } 
        }
        return view(Helpers::getViewTemplateHeaderByAgent().".address.list", ["addresses" => $addresses, "selected_id" => $selected_id,
            "message_type"=> $message_type, "message"=> $message]);
    }

    /***
     * 新增或更改地址
     * 
     */
    public function show(Request $request, $id)
    {
        $message_type = config('constants.PROMPT_INFO');
        $message = '';
        
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }   
              
        $api_client = new ApiClient();
        $address = null;
        if ($api_client->get('/api/addresses/' . $id)) {
            $address = $api_client->getBody();
        }
        //dd($address['zone']);
        return view(Helpers::getViewTemplateHeaderByAgent().".address.item", ["address" => $address, "message_type"=> $message_type,
            "message"=> $message]);
    }
    
    public function add(Request $request)
    {
        return view("mobile.address.item");
    }

    public function store(Request $request) {
        if(Auth::guest()) {
            return redirect('/customer/account');
        }
        $district_id = $request->has('district') ? $request->input('district') : "";
        $city_id = $request->has('city') ? $request->input('city') : "";
        $province_id = $request->has('province') ? $request->input('province') : "";
        if($district_id!='') {
            $zone_id = $district_id;
        } elseif($city_id!='') {
            $zone_id = $city_id;
        } elseif($province_id!=''){
            $zone_id = $province_id;
        } else {
            $zone_id = '';
        }
        $consignee_name = $request->has('consignee_name')?$request->input('consignee_name'):"";
        $consignee_phone = $request->has('consignee_phone')?$request->input('consignee_phone'):"";
        $details = $request->has('details') ? $request->input('details'):"";
        $is_default = $request->has('is_default') ? 1 : 0 ;

        $customer = Auth::user();
        $user_id = $customer->id;

        if (!$zone_id) {
            throw new Exception("找不到相应的地区！");
        }
        if (!$user_id) {
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
        if ($default_address && $is_default == 1) {
            $default_address->is_default = 0;
            $default_address->save();
        }
        try {
            Address::create([
                'customer_id' => $user_id,
                'zone_id' => $zone_id,
                'consignee_name' => $consignee_name,
                'consignee_phone' => $consignee_phone,
                'details' => $details,
                'is_default' => $is_default
            ]);
        } catch (Exception $ex) {
            Log::error("create address error:".$ex->getMessage());
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
            return $response;
        }
        return redirect('/my/address');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws Exception
     */
    public function update(Request $request,$id) {
        if(Auth::guest()) {
            return redirect('/customer/account');
        }
        $district_id = $request->has('district') ? $request->input('district') : "";
        $city_id = $request->has('city') ? $request->input('city') : "";
        $province_id = $request->has('province') ? $request->input('province') : "";
        if($district_id!='') {
            $zone_id = $district_id;
        } elseif($city_id!='') {
            $zone_id = $city_id;
        } elseif($province_id!=''){
            $zone_id = $province_id;
        } else {
            $zone_id = '';
        }
        $consignee_name = $request->has('consignee_name')?$request->input('consignee_name'):"";
        $consignee_phone = $request->has('consignee_phone')?$request->input('consignee_phone'):"";
        $details = $request->has('details') ? $request->input('details'):"";
        $is_default = $request->has('is_default') ? 1 : 0 ;

        $customer = Auth::user();
        $user_id = $customer->id;

        if (!$zone_id) {
            throw new Exception("找不到相应的地区！");
        }
        if (!$user_id) {
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
        if ($default_address && $is_default == 1) {
            $default_address->is_default = 0;
            $default_address->save();
        }
        try {
            Address::where('id',$id)->update([
                'customer_id' => $user_id,
                'zone_id' => $zone_id,
                'consignee_name' => $consignee_name,
                'consignee_phone' => $consignee_phone,
                'details' => $details,
                'is_default' => $is_default
            ]);
        } catch (Exception $ex) {
            Log::error("create address error:".$ex->getMessage());
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
            return $response;
        }
        return redirect('/my/address');
    }
}