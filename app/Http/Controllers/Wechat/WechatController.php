<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;

use Log;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Helpers;

use App\Models\Oauth;
use App\Customer;

use EasyWeChat\Foundation\Application;

class WechatController extends Controller
{
    //
    public function index(Request $request){
        return 'index';
    }

    public function oauthCallback(Application $wechat){

        $request = app()->request;
        Log::info($request->query());

        $user = null;
        $backurl = $this->getTargetUrl($request);
        session(['wechat.backurl' => $backurl]);
        if ($request->has('state') && $request->has('code')) {
            $user = $wechat->oauth->user();
            session(['wechat.oauth_user' => $user]);
        }

        if($user){
            $id = $user->id;
            $uid = $user->token->unionid;
            $oauth = Oauth::where('wechat_unionid', $uid)->first();
            if(!$oauth){
                return view('mobile.customer.completeinfo', ['user'=> $user]);
            }else{
                $c = Customer::find($oauth->user_id);
            }
            Auth::login($c);
            session()->put('Customer', $c->getAttributes());
            $backurl = session('wechat.backurl');
            if(empty($backurl)){
                $backurl = '/customer/info';
            }
            return redirect()->to($backurl);
        }else{
            Log::info('webchat login error');
            return redirect('/');
        }
    }

    public function register_step2(Request $request){
        $u['mobile'] = $request->input('mobile_number');
        $u['password'] = $request->input('user_password');
        $u['confirm_password'] = $request->input('confirm_password');
        $u['name'] = $request->input('user_name');
        $u['email'] = $request->input('email');
        $u['mobile_code'] = $request->input('mobile_code');
        $err = [];
        if($u['password'] != $u['confirm_password']){
            $err['password'] = '密码不一致';
        }
        if($u['mobile_code'] != session()->get('smsCode.mobile_code')){
            $err['sms'] = "验证码错误!";
        }
        if($u['mobile'] != session()->get('smsCode.mobile_number')){
            $err['sms_phone'] = "验证码对应的手机号不一致!";
        }
        if(!empty($err)){
            return view('mobile.customer.completeinfo', ['u' => $u, 'user' => $user, 'err' => $err]);
        }
        $user = session('wechat.oauth_user');
        if($user){
            $id = $user->id;
            $uid = $user->token->unionid;
            $oauth = Oauth::where('wechat_unionid', $uid)->first();
            if(!$oauth){
                $c = Customer::where('phone', '=', $u['mobile'])->first();
                if(!$c){
                    $c = new Customer();
                }
                $c->user_name = $u['name'].substr($user->id, 0, 7);
                $c->nick_name = $user->nickname;
                $c->phone = $u['mobile'];
                $c->email = $u['email'];
                $c->password = bcrypt($u['password']);
                $c->sex = $user->original['sex'];
                $c->portrait = $user->avatar;
                $c->is_validated = 1;
                $c->save();
                $oauth = new Oauth();
                $oauth->user_id = $c->id;
                $oauth->channel = 'weixin';
                $oauth->client_id = '';
                $oauth->open_id = $id;
                $oauth->wechat_unionid = $uid;
                $oauth->info = base64_encode(serialize($user));
                $oauth->save();
            }else{
                $c = Customer::find($oauth->user_id);
            }

            Auth::login($c);
            session()->put('Customer', $c->getAttributes());
            $backurl = session('wechat.backurl');
            if(empty($backurl)){
                $backurl = '/customer/info';
            }
            return redirect()->to($backurl);

        }else{
            Log::info('webchat register2 error, not found session');
            return redirect('/');
        }
    }

    public function serve(Application $wechat){
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        Log::info(Helpers::isWechat());

        $wechat->server->setMessageHandler(function ($message) {
            Log::info($message->FromUserName);
            switch ($message->MsgType) {
              case 'event':
                  return $message;
                  break;
              case 'text':
                  return 'text';
                  break;
              case 'image':
                  return 'image';
                  break;
              case 'voice':
                  return 'voice';
                  break;
              case 'video':
                  return 'video';
                  break;
              case 'location':
                  return $message->MsgType;
                  break;
              case 'link':
                  return $message->MsgType;
                  break;
                  // ... 其它消息
              default:
                  return $message->MsgType;
                  break;
            }
            // ...
        });

        Log::info('return response.');

        return $wechat->server->serve();
    }

    /**
     * Build the target business url.
     *
     * @param Request $request
     *
     * @return string
     */
    public function getTargetUrl($request)
    {
        $queries = $request->query();
        $backurl = isset($queries['back_url']) ? $queries['back_url'] : '/customer/info';
        return $backurl;
    }
}