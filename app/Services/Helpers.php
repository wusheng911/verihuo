<?php

/****
 * 公用函数类
 * 
 * 
 */

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Facades\Agent;
use Log;

class Helpers
{ 
    /**
     * Verify if the user is logged in successfully. 
     */
    public static function isUserLoggedIn()
    {
        return Session::has('Customer') && (intval(Session::get('Customer.is_validated')) > 0);
    }

    /**
     * Get current login user from session
     * 
     * @return Array An array of the user properties, like $user['id']
     */
    public static function getCurrentUser(){
        if(self::isUserLoggedIn()){
            return Session::get('Customer');
        } else {
            return [];
        }
    }
    
    /**
     * Exam if the provided value is a valid mobile phone number
     * 
     * @param type $value the mobile phone number to test
     * @return type
     */
    public static function isMobileNumber($value) 
    {
        return is_numeric($value) && is_int($value+0) && (strlen(strval($value)) == 11);
    }

    /**
     * Exam if the provided value is a valid email format 
     * 
     * @param type $value the email value to test
     * @return boolean
     */
    public static function isEmail($value) 
    {
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if(preg_match($pattern, $value))
        {
            $user_name = preg_replace( $pattern ,"$1", $value);
            return $user_name;
        }
        else
        {
            return false;
        }    
    }   
    
    /**
     * Compile the password to a hashed string
     * 
     * @param type $password password plate text value
     * @param type $salt salt value which is recorded in customer.salt
     * @return type
     */
    public static function compilePassword($password, $salt = null) 
    {
        $md5password = md5($password);
        if ($salt) {
            return md5($md5password . $salt);
        } else {
            return $md5password;
        }    
    }    
    
    /**
     * Create a random string
     * 
     * @param type $length string length
     * @param type $numeric 
     * @return string
     */
    public static function random($length = 6 , $numeric = 0) {
        //PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }  
    
    /**
     * Verify if the provided url is a valid referal (jump url), only the listed 
     * URL patterns can do jump after login
     * 
     * @param type $referer A Url
     * @return boolean
     */
    public static function validateJumpUrl($jumpUrl) 
    {
        $validPatterns = [
            '/view',
            '/news',
            '/shop',
            '/my',
            '/m/news'
        ];
        $result = false;
        
        if (!empty($jumpUrl)) {
            $str = str_replace('http://', '', $jumpUrl);
            $str = str_replace(filter_input(INPUT_SERVER, 'HTTP_HOST'), '', $str); 
            foreach($validPatterns as $validPattern) {
                if (stripos($str, $validPattern) !== false) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }   
    
    /**
     * Set JumpUrl, by write it to Session 'jumpUrl' field
     */
    public static function setJumpUrl($url = '') 
    {
        $str = empty($url)?filter_input(INPUT_SERVER, 'HTTP_REFERER'):$url;
              
        if (!empty($str)) {
            if (self::validateJumpUrl($str)) {
                Session::put('jumpUrl', $str);
                Log::info("Set Jump Url: " . $str);
            }
        } 
    }    
    
    public static function getJumpUrl()
    {
        return Session::get('jumpUrl');
    }
    
    public static function hasJumpUrl() 
    {
        return Session::has('jumpUrl');
    }
    
    public static function post($curlPost, $url) 
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    public static function xmlToArray($xml) 
    {
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if (preg_match_all($reg, $xml, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i++) {
                $subxml = $matches[2][$i];
                $key = $matches[1][$i];
                if (preg_match($reg, $subxml)) {
                    $arr[$key] = self::xmlToArray($subxml);
                } else {
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }  
    
    /**
     * 发起一个 HTTP(S) 请求, 并返回响应文本
     *
     * @param array 错误信息: array($errorCode, $errorMessage)
     * @param string url
     * @param array 参数数组
     * @param string 请求类型    GET|POST
     * @param int 超时时间
     * @param array 扩展的包头信息
     * @param array $extOptions
     *
     * @return string
     */
    public static function curl_request_text(&$error, $url, $params = array(), $method = 'GET', $timeout = 15, $extheaders = null, $extOptions = null)
    {
        if(!function_exists('curl_init')) exit('Need to open the curl extension.');
 
        $method = strtoupper($method);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        switch($method)
        {
          case 'POST':
              curl_setopt($curl, CURLOPT_POST, TRUE);
              if(!empty($params))
              {
                  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
              }
              break;
 
          case 'DELETE':
          case 'GET':
              if($method == 'DELETE')
              {
                  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
              }
              if(!empty($params))
              {
                  $url = $url . (strpos($url, '?') ? '&' : '?') . (is_array($params) ? http_build_query($params) : $params);
              }
              break;
        }
        curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
        curl_setopt($curl, CURLOPT_URL, $url);
        if(!empty($extheaders))
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, (array)$extheaders);
        }
        if(!empty($extOptions)) {
            foreach($extOptions as $key => $value) curl_setopt($curl, $key, $value);
        }
        $response = curl_exec($curl);
        curl_close($curl);
 
        return $response;
    }  
    
    public static function callWeiXinApi($apiUrl,$params = array(), $method = 'GET'){
	    $resultText = self::curl_request_text($error, $apiUrl, $params, $method);
	    $result = json_decode($resultText,true);
	    if(0 === strncmp('{', ltrim(substr($resultText, 0, 10)), 1)) {
            $result = json_decode($resultText, true);
        }
        else if(strpos($resultText, "callback") !== false) {
            $lpos = strpos($resultText, "(");
            $rpos = strrpos($resultText, ")");
            $errorText = substr($resultText, $lpos + 1, $rpos - $lpos -1);
            $result = json_decode($errorText, true);
        }
        else {
            parse_str($resultText, $result);
        }
	    return $result;
    }
    
    public static function getWeiXinUserInfo($accessToken, $openId){
        $result = self::callQQApi("https://api.weixin.qq.com/sns/userinfo?access_token=".$accessToken."&openid=".$openId);
        return $result;
    }
    
    public static function callQQApi($apiUrl, $params = array(), $method = 'GET') {
        $resultText = self::curl_request_text($error, $apiUrl, $params, $method);
        if(0 === strncmp('{', ltrim(substr($resultText, 0, 10)), 1)) {
            $result = json_decode($resultText, true);
        }
        else if(strpos($resultText, "callback") !== false) {
            $lpos = strpos($resultText, "(");
            $rpos = strrpos($resultText, ")");
            $errorText = substr($resultText, $lpos + 1, $rpos - $lpos -1);
            $result = json_decode($errorText, true);
        }
        else {
            parse_str($resultText, $result);
        }
        return $result;
    }

    public static function getQQOpenId($accessToken) {
        $result = self::callQQApi("https://graph.qq.com/oauth2.0/me?access_token={$accessToken}");
        return $result;
    }
    
    public static function getQQUserInfo($accessToken, $openId){
        $result = self::callQQApi("https://graph.qq.com/user/get_user_info?access_token={$accessToken}&oauth_consumer_key=101303225&openid={$openId}");
        return $result;
    }    
    
    public static function getRealIp(){ 
        $ip=false; 
        if(!empty(filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP'))){ 
            $ip=filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP'); 
        }
        if(!empty(filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR'))){ 
            $ips=explode (', ', filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR')); 
            if($ip){ array_unshift($ips, $ip); $ip=FALSE; }
            for ($i=0; $i < count($ips); $i++){
                if(!preg_match('/^(10│172.16│192.168)./i', $ips[$i])){
                    $ip=$ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : filter_input(INPUT_SERVER, 'REMOTE_ADDR')); 
    }
    
    
    public static function isMobile()
    {
        if (config('app.env') !== 'production' && (config('chaohun.simulate_mobile') === true)) {
            return true;
        } else {
            return Agent::isMobile();
        }
    }
    
    
    public static function getViewTemplateHeaderByAgent() 
    {
        return self::isMobile()?'mobile':'pc';
    }
    
    public static function getShowPrice($price)
    {
        return (floor($price) == $price)?floor($price):$price;
    }
    
    public static function isOrderProcessing()
    {
        return Session::has('Order');
    }

    public static function isWechat()
    {
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }	
        return false;
    }
    
    public static function getPlatform()
    {
        return Session::has('platform')?Session::get('platform'):'';
    }
    
    public static function getHomeUrl($platform)
    {
        if (isset($platform) && (strtolower($platform) == 'shop')) {
            return '/shop';
        } else {
            return '/';
        }
    }
    public static function loadExcelFile($path,$separator=null)
    {
       $file = fopen($path,"r",$separator);
       if(!empty($file))
       {
          $datas = array();
          $datas = fgetcsv($file);
          fclose($file); 
           return $datas;
       } 
       return null;
    }
    
}

