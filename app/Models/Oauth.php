<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Helpers;
use App\Customer;
use Exception;
use Log;

class Oauth extends Model
{
    /**
     * The name of table.
     * 
     * @var string
     */
    protected $table = 'oauth';    
    
    
    public static function getOauthUser($channel, $openId) {
        $oauth_user = Oauth::where('channel', $channel)->where('open_id', $openId)->get()->first();
        return $oauth_user;
    }
    public static function getOauthUserByUnionid($channel, $unionid) {
        $oauth_user = Oauth::where('channel', $channel)->where('wechat_unionid', $unionid)->get()->first();
        return $oauth_user;
    }
    /*
     * 获取通道用户信息
     */
    public static function getCustomerId($code, $channel,$userinfo=[]) {
        $uid = null;
      
        if($channel == "qq"){
            $callUrl = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=101303225&client_secret=f5b8a5d685f4a8a7744380578eafdf6d&code={$code}&redirect_uri=http://www.51chaohun.com/members/login/channel/qq"; 
            $result = Helpers::callQQApi($callUrl);
            Log::info('qq->Helpers::callQQApi()::' . PHP_EOL . print_r($result, true));
            if(isset($result['access_token'])) {
                $token = $result['access_token'];
                $result = array_merge($result, Helpers::getQQOpenId($token));
            }
            Log::info('Helpers::getQQOpenId()::' . PHP_EOL . print_r($result, true));
            if(isset($result['openid'])){
                $oauth = self::getOauthUser($channel, $result['openid']);
                Log::info('qq->self::getOauthUser()::' . PHP_EOL . print_r($oauth, true));
                if (isset($oauth->user_id)) {
                    $uid = $oauth->user_id;
                    return $uid;
                } else {
                    $result = array_merge($result, Helpers::getQQUserInfo($token, $result['openid']));
                    Log::info('qq->Helpers::getQQUserInfo()::' . PHP_EOL . print_r($result, true));
                    $uid = self::createCustomer($result, $channel,$userinfo);
                    if ($uid) {
                        try {
                            $oauth = new Oauth();
                            $oauth->user_id = $uid;
                            $oauth->channel = 'qq';
                            $oauth->client_id = $result['client_id'];
                            $oauth->open_id = $result['openid'];
                            $oauth->info = json_encode($result);
                            $oauth->save();   
                        } catch (Exception $ex) {
                            if ($uid) {
                                Customer::find($uid)->delete();
                            }
                            Log::error('qq-> new Oauth Record::' . $ex);
                        }
                    }
                }
            }
        } else if($channel == 'weixin'){
            $callUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx397120b793e960d5&secret=38c1097adfb7d4b17ff1d463ccfef430&code=".$code."&grant_type=authorization_code";
            $result = Helpers::callWeiXinApi($callUrl);
            Log::info('weixin->Helpers::callWeiXinApi()::'. PHP_EOL . print_r($result, true));
            if(isset($result['openid']) && isset($result['access_token'])){
                $result = Helpers::getWeiXinUserInfo($result['access_token'],$result['openid']);
                $oauth =  self::getOauthUserByUnionid($channel, $result['unionid']);
                Log::info('weixin->self::getOauthUserByUnionid()::' . PHP_EOL . print_r($oauth, true));
                if (isset($oauth->user_id)) {
                    $uid = $oauth->user_id;
                    return $uid;
                } else {
                    /* $result = Helpers::getWeiXinUserInfo($result['access_token'],$result['openid']); */
                    Log::info('weixin->Helpers::getWeiXinUserInfo()::' . PHP_EOL . print_r($result, true));
                    /* if(empty($userinfo['phone'])){ */
                    return $result; 
                    /* } */
                    /* $uid = self::createCustomer($result, $channel,$userinfo); */
                    /* if ($uid) { */
                    /*     try { */
                    /*         $oauth = new Oauth(); */
                    /*         $oauth->user_id = $uid; */
                    /*         $oauth->channel = 'weixin'; */
                    /*         $oauth->wechat_unionid = $result['unionid']; */
                    /*         $oauth->open_id = $result['openid']; */
                    /*         $oauth->info = json_encode($result); */ 
                    /*         $oauth->save(); */                        
                    /*     } catch (Exception $ex) { */
                    /*         if ($uid) { */
                    /*             Customer::find($uid)->delete(); */
                    /*         } */
                    /*         Log::error('weixin-> new Oauth Record::' . $ex); */
                    /*     } */
                    /* } */
                }
            }
        }
        return $uid;
    }      
    
    
    public static function createCustomerByForm($channel,$userinfo=[]) {
        $mobile_number = $userinfo['mobile_number'];
        $customer = Customer::where('phone',$mobile_number)->first();
        if(empty($customer)){
            $customer = new Customer();
        }
        try {
            if($channel == 'qq'){
                $m = ['client_id' => $userinfo['client_id'],
                    'openid' => $userinfo['openid'],
                    'nickname' => $userinfo['nickname'],
                    'gender' => $userinfo['gender'],
                    'province' => $userinfo['province'],
                    'city' => $userinfo['city'],
                    'figureurl' => $userinfo['figureurl'],
                ];
            }else if($channel == 'weixin'){
                $m = ['client_id' => $userinfo['unionid'],
                    'openid' => $userinfo['openid'],
                    'nickname' => $userinfo['nickname'],
                    'gender' => $userinfo['sex'],
                    'province' => $userinfo['province'],
                    'city' => $userinfo['city'],
                    'figureurl' => $userinfo['headimgurl'],];
            }       
            $customer->sex = $m['gender'];
            $customer->aite_id = $channel.'_'.$m['openid'];
            $customer->is_validated = 1;
            $customer->portrait = $m['figureurl'];
            $customer->last_login = date('Y-m-d H:i:s');
            $customer->nick_name = $m['nickname'];
            $customer->password = Helpers::compilePassword('takeflight');

            $customer->user_name = isset($userinfo['user_name'])?$userinfo['user_name']:'';
            $customer->phone = isset($userinfo['mobile_number'])?$userinfo['mobile_number']:'';
            $customer->email = isset($userinfo['email'])?$userinfo['email']:'';
            $customer->password = isset($userinfo['user_password'])?bcrypt($userinfo['user_password']):Helpers::compilePassword('takeflight');
            $customer->save();

        } catch(Exception $ex) {
            Log::error("{$channel}->createCustomer()::" . $ex);
            return null;
        }
        return $customer->id;
    }
    public static function createCustomer($result, $channel,$userinfo=[]) {
        $customer = new Customer();
        try {
            if($channel == 'qq'){
                $m = ['client_id' => $result['client_id'],
                    'openid' => $result['openid'],
                    'nickname' => $result['nickname'],
                    'gender' => $result['gender'],
                    'province' => $result['province'],
                    'city' => $result['city'],
                    'figureurl' => $result['figureurl'],
                ];
            }else if($channel == 'weixin'){
                $m = ['client_id' => $result['unionid'],
                    'openid' => $result['openid'],
                    'nickname' => $result['nickname'],
                    'gender' => $result['sex'],
                    'province' => $result['province'],
                    'city' => $result['city'],
                    'figureurl' => $result['headimgurl'],];
            }       
            $customer->sex = $m['gender'];
            $customer->aite_id = $channel.'_'.$m['openid'];
            $customer->is_validated = 1;
            $customer->portrait = $m['figureurl'];
            $customer->last_login = date('Y-m-d H:i:s');
            $customer->nick_name = $m['nickname'];
            $customer->password = Helpers::compilePassword('takeflight');

            $customer->user_name = isset($userinfo['user_name'])?$userinfo['user_name']:'';
            $customer->phone = isset($userinfo['mobile_number'])?$userinfo['mobile_number']:'';
            $customer->email = isset($userinfo['email'])?$userinfo['email']:'';
            $customer->password = isset($userinfo['user_password'])?bcrypt($userinfo['user_password']):Helpers::compilePassword('takeflight');
            $customer->save();

        } catch(Exception $ex) {
            Log::error("{$channel}->createCustomer()::" . $ex);
            return null;
        }
        return $customer->id;
    }
}
