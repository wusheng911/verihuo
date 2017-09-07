<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Exception;
use Illuminate\Http\Request;
use App\Services\Helpers;
use App\Services\ApiClient;

class InvoiceController extends Controller
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
            /* selected_id用于从下单页选择发票信息时，传入上次选择的发票信息id, 
             * 如果上次无选择发票信息，则传入0表示，从下单页进入的发票信息列表页，从而与从个人中心进入发票列表页的情况区分
             */           
            $selected_id = $request->has("selected_id")?$request->input("selected_id"):'';
            $customer = Helpers::getCurrentUser();

            $api_client = new ApiClient();
            $invoices = null;
            if ($api_client->get('/api/invoices?user_id=' . $customer["id"])) {
                $invoices = $api_client->getBody();
            } 
        }
        
        return view("mobile.address.list", ["invoices" => $invoices, "selected_id" => $selected_id,
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
             
        return view("mobile.address.item", ["address" => $address, "message_type"=> $message_type,
            "message"=> $message]);
    }
    
    public function add(Request $request)
    {
        return view("mobile.address.item");
    }
}
