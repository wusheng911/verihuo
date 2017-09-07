<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Log;
use Auth;
use App\Http\Requests;
use App\Services\Helpers;
use App\Customer;
use App\Models\Oauth;
use App\Services\ApiClient;
use App\Models\Shop\Order;
use App\Models\Shop\OrderStatus;

class CustomerController extends Controller
{
    
    protected function updateCustomerSession($customer) {
        session()->put('Customer', $customer->getAttributes());
                
        //get cart info
        $api_client = new ApiClient();
        if ($api_client->get("/api/carts?user_id={$customer->id}")) {
            $carts = $api_client->getBody();
            if (!empty($carts)) {
                $cart_quantity = (count($carts) > 0)?$carts[0]['total_quantity']:0;
                if ($cart_quantity > 0) {
                    session()->put('Customer.cart_quantity', $cart_quantity);
                }
            }
        }
                
    }
    
    public function __construct() {
        Helpers::setJumpUrl();
    }
  
  
    public function registerForm(Request $request) {

        if(Auth::check()){
            return redirect('/customer/account');
        }
        $message_type = config('constants.PROMPT_WARNING');
        $message = '';
        
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }

        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.register', 
            ['message_type' => $message_type, 'message' => $message]);
    }

    public function register(Request $request) {
        $message_type = config('constants.PROMPT_WARNING');
        $message = '';
        
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }
        
        $user_name = $request->input('user_name');
        $mobile_number = $request->input('mobile_number');
        $password = $request->input('user_password');
        $email = $request->input('email');
        $mobile_code = $request->input('mobile_code');
            
        $request->session()->flash('old', $request->input());
        if (!Helpers::isMobileNumber($mobile_number)) {
            $message = "{$mobile_number}不是有效的手机号!";
        }

        if(!empty($email) && !Helpers::isEmail($email)) {
            $message = "{$email}不是有效的邮箱!";
        }

        if (!empty($mobile_code) and ($request->session()->get('smsCode.mobile_number') == $mobile_number) 
            and ($request->session()->get('smsCode.mobile_code') == $mobile_code)) {
            ;
        } else {
            $message = "手机短信验证码不正确!";
        }

        if (empty($message)) {        
            //$salt = Helpers::random(4,1);
            $customer = new Customer;

            $customer->user_name = $user_name;
            $customer->phone = $mobile_number;
            $customer->email = $email;
            $customer->password = bcrypt($password);
            $customer->is_validated = 1;
            //$customer->salt = $salt;

            $customer->save();
            if ($customer->id > 0) {
                //用户注册成功，设置 Cookie，加密直接用 get_authcode 函数，用户使用自己的函数
                //setcookie('51chaohunportal_auth', $this->get_authcode($this->uid."\t".$this->username, 'ENCODE'), 0, '/', '', false, false);

                Auth::login($customer);
                //保存客户信息到Session
                $request->session()->put('Customer', $customer->getAttributes());
                $backurl = $request->session()->get('_backurl');
                if(empty($backurl)){
                    $backurl='/customer/account' ; 
                }
                return redirect($backurl)
                    ->with('promptMessage', [
                               'message_type' => config('constants.PROMPT_INFO'), 
                               'message' => '注册成功!'
                           ]);
            } else {
                $message = '无法创建一个有效的用户!';
            } 
        }
      
        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.register', 
                    ['message_type' => $message_type, 'message' => $message]);
    }
    
    public function logout(Request $request)
    {        
        $home_url = Helpers::getHomeUrl(Helpers::getPlatform());   
        $request->session()->flush();
        //if the current platform is shop, then redirect to shop home
        return redirect($home_url)
            ->with('promptMessage', [
                'message_type' => config('constants.PROMPT_INFO'), 
                'message' => '退出成功!'
            ]);
    }
    
    public function loginForm() {

        $message_type = config('constants.PROMPT_WARNING');
        $message = '';

        if(Auth::check()){
            return redirect('/customer/account');
        }

        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.login', 
            ['message_type' => $message_type, 'message' => $message]);
    }

    public function oauthRegister(Request $request){
        $channel = $request->input('channel');
        $message_type = config('constants.PROMPT_WARNING');
        $message = '';
        if($channel =='weixin'){
            $userInfo = array();
            $userInfo['oauth_code']=$request->input('oauth_code');
            $userInfo['user_name'] = $request->input('user_name');
            $userInfo['mobile_number']=$request->input('mobile_number');
            $userInfo['user_password']=$request->input('user_password');
            $userInfo['email']=$request->input('email');
            $userInfo['headimgurl']=$request->input('headimgurl');
            $userInfo['mobile_code']=$request->input('mobile_code');
            $confirmPassword = $request->input('confirm_password');
            $mobile_number = $userInfo['mobile_number'];
            $email = $userInfo['email'];
            $mobile_code = $userInfo['mobile_code'];
            
            if (!Helpers::isMobileNumber($mobile_number)) {
                $message = "{$mobile_number}不是有效的手机号!";
            }

            if(!empty($email) && !Helpers::isEmail($email)) {
                $message = "{$email}不是有效的邮箱!";
            }

            if (!empty($mobile_code) and ($request->session()->get('smsCode.mobile_number') == $mobile_number) 
                and ($request->session()->get('smsCode.mobile_code') == $mobile_code)) {
                ;
            } else {
                $message = "手机短信验证码不正确!";
            }

            if(empty($message)){

                $oauthInfo = session()->get('OauthInfo');
                $userInfo = array_merge($userInfo,$oauthInfo);
                $uid = Oauth::createCustomerByForm($channel,$userInfo);
                if ($uid) {
                    try {
                        $oauth = new Oauth();
                        $oauth->user_id = $uid;
                        $oauth->channel = 'weixin';
                        $oauth->wechat_unionid = $userInfo['unionid'];
                        $oauth->open_id = $userInfo['openid'];
                        $oauth->info = json_encode($userInfo); 
                        $oauth->save();                        
                    } catch (Exception $ex) {
                        if ($uid) {
                            Customer::find($uid)->delete();
                        }
                        Log::error('weixin-> new Oauth Record::' . $ex);
                    }
                }
                if ($uid) {
                    $customer = Customer::find($uid);
                    Auth::login($customer);
                    $this->updateCustomerSession($customer);
                    if (Helpers::hasJumpUrl()) {
                        return redirect(Helpers::getJumpUrl());
                    } else {
                        return redirect(Helpers::getHomeUrl(Helpers::getPlatform()))
                            ->with('promptMessage', [
                                'message_type' => config('constants.PROMPT_INFO'), 
                                'message' => '登录成功!'
                            ]);
                    }
                }
            }else{
                    /* return back()->with('userInfo',$userInfo); */
                    return view('auth.oauthregister',['message'=>$message,'channel'=>$channel,'headimgurl'=>$userInfo['headimgurl'],'userInfo'=>$userInfo]);
            }
        }
    }
    public function oauthLogin(Request $request,$oauth_type = null) {

        $code = $request->input('code');
        if($oauth_type == 'qq' || $oauth_type == 'weixin') {
            $uid = Oauth::getCustomerId($code, $oauth_type);
            if(!is_numeric($uid)){
                session()->put('OauthInfo',$uid);
                $headUrl = '';
                if($oauth_type == 'qq'){
                    $headUrl = $uid['figureurl'];
                }elseif($oauth_type == 'weixin'){
                    $headUrl = $uid['headimgurl'];
                }
                return view('auth.oauthregister',['channel'=>$oauth_type,'headimgurl'=>$headUrl]);
            }
            if ($uid) {
                $customer = Customer::find($uid);
                Auth::login($customer);
                $this->updateCustomerSession($customer);
                if (Helpers::hasJumpUrl()) {
                    return redirect(Helpers::getJumpUrl());
                } else {
                    return redirect(Helpers::getHomeUrl(Helpers::getPlatform()))
                        ->with('promptMessage', [
                            'message_type' => config('constants.PROMPT_INFO'), 
                            'message' => '登录成功!'
                        ]);
                }
            }
        }
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // \Log::info($email);
        // \Log::info($password);

        $c['email'] = $email;
        $c['password'] = $password;

        $r['status'] = 0;
        $r['err'] = '';
        if (Auth::attempt($c)) {
            $customer = Auth::user();
            $r['c'] = $customer;
            //$this->updateCustomerSession($customer);
            $r['status'] = 1;
        } else {
            $r['err'] = "您的登录帐号不存在，或密码错误!";            
        }
        return response()->json($r);
    }

    public function login2(Request $request, $oauth_type = null)
    {
        $message_type = config('constants.PROMPT_WARNING');
        $message = '';
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }

        $iname = $request->input('login_name');
        if(Helpers::isMobileNumber($iname)) {
            $credentials['phone'] = $iname;
        } elseif (Helpers::isEmail($iname)) {
            $credentials['email'] = $iname;
        } else {
            $credentials['user_name'] = $iname;
        }
        $credentials['password'] = $request->input('user_password');

        if (Auth::attempt($credentials)) {
            $customer = Auth::user();
            if (intval(Auth::user()->is_validated) > 0) {
                //把customer存入session
                $this->updateCustomerSession($customer);
                if (Helpers::hasJumpUrl()) {
                    return redirect(Helpers::getJumpUrl());
                } else {
                    return redirect(Helpers::getHomeUrl(Helpers::getPlatform()))
                        ->with('promptMessage', [
                            'message_type' => config('constants.PROMPT_INFO'), 
                            'message' => '登录成功!'
                        ]);
                }
            } else {
                $message = "您的邮箱帐号{$customer->email}还未通过验证,请登录您的邮箱完成注册!";
            }  
        } else {
            $message = "您的登录帐号不存在，或密码错误!";            
        }
        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.login', 
            ['message_type' => $message_type, 'message' => $message]);
    }
    
    public function mobileAgreement(Request $request) 
    {
        return view('mobile.customer.agreement');
    }

    public function resetPasswordForm() {
        $message_type = config('constants.PROMPT_WARNING');
        $message = '';
        
        if (session()->has('promptMessage')) {
            $message_type = session()->get('promptMessage.message_type');
            $message = session()->get('promptMessage.message');
        }

        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.resetpassword', ['message_type' => $message_type, 'message' => $message]);
    }
    
    public function resetPassword(Request $request)
    {    
        $message_type = config('constants.PROMPT_WARNING');
        $message = '';
        
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }
        
        if ($request->isMethod('POST')) {
            $mobile_number = $request->input('mobile_number');
            $password = $request->input('user_password');
            $mobile_code = $request->input('mobile_code');
            
            if (Customer::registeredBy($mobile_number) != 'phone')
            {
                $message = '用户不存在!';
            }
            
            if (!empty($mobile_code) and ($request->session()->get('smsCode.mobile_number') == $mobile_number) 
              and ($request->session()->get('smsCode.mobile_code') == $mobile_code)) {
                ;
            } else {
                $message = "手机短信验证码不正确!";
                var_dump($request->session()->get('smsCode'));
            }

            if (empty($message)) {      
                try {
                    $customer = Customer::where('phone', $mobile_number)->first();
                    $customer->password = bcrypt($password);
                    $customer->password_type = null;
                    $customer->is_validated = 1;
                    $customer->save();
                    return redirect('/customer/login')->with('promptMessage', ['message_type' => config('constants.PROMPT_INFO'), 'message' => '密码修改成功!']);
                } catch(\Exception $ex) {
                    $message = '修改密码操作失败!';
                } 
            }
        }
        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.resetpassword', ['message_type' => $message_type, 'message' => $message]);
    }

    /**
     * 个人中心
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function account(Request $request)
    {
        if(!Auth::check()) {
            return redirect()->to(action('CustomerController@login'));
        }
        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.account',
                    ['customer' => Auth::user()]);
    }

    /**
     * 个人基本信息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userInfo(Request $request)
    {
        if(!Auth::check()) {
            return redirect()->to(action('CustomerController@login'));
        }
        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.accountinfo',
                    ['customer' => Auth::user()]);
    }
    /**
     * 查看手机号是否被注册过
     * @param Request $request 手机号
     */
    public function isRegisterNumber(Request $request){
        $number = $request->input('phone');
        $customer = Customer::where('phone',$number)->get();
        $isRegister = false;
        if(count($customer)>0){
           $isRegister = true; 
        }
        return response()->json($isRegister);
    }
	public function studentRegister(Request $request){
		$inputs = $request->input('customer');
		$user_name = $inputs['name'];
		$password = $inputs['password'];
		$email = $inputs['email'];
		$message = null;
        if (empty($message)) {        
            //$salt = Helpers::random(4,1);
            $customer = new Customer;

            $customer->user_name = $user_name;
            $customer->email = $email;
            $customer->password = bcrypt($password);
            $customer->is_validated = 1;
            //$customer->salt = $salt;

            $customer->save();
            if ($customer->id > 0) {
                //用户注册成功，设置 Cookie，加密直接用 get_authcode 函数，用户使用自己的函数
                //setcookie('51chaohunportal_auth', $this->get_authcode($this->uid."\t".$this->username, 'ENCODE'), 0, '/', '', false, false);

				DB::table('customer_exp1')->insert(['customer_id'=>$customer->id]);
				DB::table('customer_exp2')->insert(['customer_id'=>$customer->id]);
				DB::table('customer_info1')->insert(['customer_id'=>$customer->id]);
				DB::table('customer_info1')->insert(['customer_id'=>$customer->id]);
				DB::table('customer_scores')->insert(['customer_id'=>$customer->id]);
				DB::table('customer_advises')->insert(['customer_id'=>$customer->id]);
                Auth::login($customer);
                //保存客户信息到Session
                $request->session()->put('Customer', $customer->getAttributes());
                return redirect("/");
            } else {
                $message = '无法创建一个有效的用户!';
            } 
        }

	}
	public function info(Request $request){
		$info = $request->input('info');	
		$user = Auth::user();
		if(!empty($user)){
			$user_id = $user->id;
			$user_scores = DB::table('customer_scores')->where('customer_id','=', $user_id)->update(array('customer_id'=>$user_id,'graduate_year'=>(int) $info['graduate_year']));
			$user->user_name = $info['name'];
			$user->save();
		}
		return redirect()->to(action('CustomerController@personal'));;
	}
	public function bg(Request $request){
		$bg = $request->input('bg');	
		$user = Auth::user();
		if(!empty($user)){
			$user->phone = $bg['phone'];	
			$user->email = $bg['email'];	
			$user->sex = $bg['gender'];	
			$user->school_name = $bg['studyingat'];	
			$user->save();
		}
		return redirect()->to(action('CustomerController@personal'));;
	}
	public function score(Request $request){
		$score = $request->input('score');	
		$user = Auth::user();
		if(!empty($user)){
			$score['customer_id'] = $user->id;
			$user_scores = DB::table('customer_scores')->where('customer_id','=', $user->id)->update($score);
		}
		return redirect()->to(action('CustomerController@personal'));;
	}
	public function exp1(Request $request){
		$exp1 = $request->input('exp1');	
		$user = Auth::user();
		if(!empty($user)){
			$exp1['customer_id'] = $user->id;
			$user_scores = DB::table('customer_exp1')->where('customer_id','=', $user->id)->update($exp1);
		}
		return redirect()->to(action('CustomerController@personal'));;
	}
	public function exp2(Request $request){
		$exp2 = $request->input('exp2');	
		$user = Auth::user();
		if(!empty($user)){
			$exp2['customer_id'] = $user->id;
			$user_scores = DB::table('customer_exp2')->where('customer_id','=', $user->id)->update($exp2);
		}
		return redirect()->to(action('CustomerController@personal'));;
	}
	public function personal(Request $request){
		$user = Auth::user();
		//$user = Auth::loginUsingId(3);
		$schoolList = [];
		if(!empty($user)){
			$user_id = $user->id;	
			$user_exp1 = DB::table('customer_exp1')->where('customer_id','=', $user->id)->first();
			$user_exp2 = DB::table('customer_exp2')->where('customer_id','=', $user->id)->first();
			$user_score = DB::table('customer_scores')->where('customer_id','=', $user->id)->first();
			$schools = DB::table('schools')->where('id','>', 0)->get();
			$archive = DB::table('customer_info1')->where('customer_id','=',$user->id)->first();
			$interestSchools = DB::table('customer_fschool')->where('customer_id','=',$user->id)->orderBy('pos','asc')->get();
			
			foreach($interestSchools as $key => $value){
				$tmpSchool = DB::table('schools')->where('id','=',$value->school_id)->first();
				if(!empty($tmpSchool)){
					$schoolList[] = $tmpSchool;
				}
			}
			$advise = DB::table('customer_advises')->where('customer_id','=',$user->id)->first();
			$report = DB::table('final_reports')->where('customer_id','=',$user->id)->first();
			return view('pc.personal',['user'=>$user,'user_exp1'=>$user_exp1,'user_exp2'=>$user_exp2,'user_score'=>$user_score,'schools'=>$schools,'schoolList'=>$schoolList,'archive'=>$archive,'advise'=>$advise,'report'=>$report]);
		}
	}
	public function ccscore(Request $request){
		$user = Auth::user();
		if(!empty($user)){
			$scores = DB::table('ccscores')->where('customer_id','=',$user->id)->orderBy('at','desc')->take(10)->get();	
			$advises = DB::table('customer_advises')->where('customer_id','=',$user->id)->orderBy('updated_at')->first();
			$json = ['scores'=>$scores,'advises'=>$advises];
			return response()->json($json);
		}
	}
	public function interestSchool(Request $request){
		
		//$user = Auth::loginUsingId(3);
		$user = Auth::user();
		if(!empty($user)){
			$school_1 = $request->input('school-1');
			$school_2 = $request->input('school-2');
			$school_3 = $request->input('school-3');
			$school_4 = $request->input('school-4');
			$school_5 = $request->input('school-5');
			$school_6 = $request->input('school-6');
			for($i=1 ; $i<7; $i++){
				$name = 'school_'.$i;	

				$index = DB::table('customer_fschool')->where('customer_id','=',$user->id)->where('pos','=',$i)->first();
				if(!empty($index)){
					DB::table('customer_fschool')->where('customer_id','=', $user->id)->where('pos','=',$i)->update(['pos'=>$i,'school_id'=>$$name]);
				}else{
					DB::table('customer_fschool')->insert(['customer_id'=>$user->id,'pos'=>$i,'school_id'=>$$name]);
				}
			}		
			return redirect()->to(action('CustomerController@personal'));;
		}
	}
	public function archive(Request $request){
		$myArchive = $request->input('archive');	
		$user = Auth::user();
		if(!empty($user)){
			$user_archive = DB::table('customer_info1')->where('customer_id','=', $user->id)->first();
			if(!empty($user_archive)){
				$user_scores = DB::table('customer_info1')->where('customer_id','=', $user->id)->update($myArchive);
			}
			return redirect()->to(action('CustomerController@personal'));;
		}
	}
}
