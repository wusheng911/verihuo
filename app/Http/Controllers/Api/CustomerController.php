<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Services\Helpers;
use App\Customer;
use Exception;
use DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
 
    /**
     * Verify if the customer be registered by provided value
     * 
     * @param string $value could be phone_number/user_name/email
     * @return \Illuminate\Http\Response
     */    
    public function registeredBy(Request $request, $value) 
    {
        try {
            $result = Customer::registeredBy($value); 
            $result = empty($result)?[]:[$result];            
            $response = ApiResponse::get(false, $result);
        } catch (Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }   
        
        return $response;              
    }
    
    
    /**
     * A Post request to send SMS and retrieve the return code
     * 
     * @param Request $request
     * @return type
     * @throws Exception
     */
    public function sendSMS(Request $request)
    {
        $sms_name = "cf_13816358030";
        $sms_passord = "13816358030";
        $md5_sms_password = "5ae35ac4cf89250cac4f067f3254c9a8";
        $mobile_code = Helpers::random(4,1);
        $message = "您的验证码是：".$mobile_code."，请不要把验证码泄露给其他人，如非本人操作，可不用理会";        

        try {
            $result = [];
          
            if ($request->has('mobile_number')) {
                $mobile_number = $request->input('mobile_number');

                /* 获取API URL */
                $sms_url = "http://106.ihuyi.com/webservice/sms.php?method=Submit";
                //密码可以使用明文密码或使用32位MD5加密
                $post_data = "account=" . $sms_name . "&password=" . $md5_sms_password . 
                    "&mobile=" . $mobile_number . "&content=" . rawurlencode($message);
                 
                $get = Helpers::post($post_data, $sms_url);
                $gets = Helpers::xmlToArray($get);

                if ($gets['SubmitResult']['code'] == 2) {
                    //success
                    $result = ['code' => 2,
                        'mobile_number' => $mobile_number,
                        'mobile_code' => $mobile_code,
                        'security_code' => time()];
                } else {
                    //failed, return error message
                    $result = ['msg' => $gets['SubmitResult']['msg']];
                } 
                
                $response = ApiResponse::get(false, $result);
            } else {
                throw new Exception("Can not found parameter 'mobile_number'");
            }          
        } catch (Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        } 
        
        return $response;   
    }
    
}
