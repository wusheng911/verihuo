<?php

namespace App;

use App\Models\Oauth;
use App\Services\Helpers;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Customer extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

    public function oauth(){
        return $this->hasOne('App\Models\Oauth', 'user_id', 'id');
    }

    public function getUserName(){
        if(!empty($this->nick_name)) {
            return $this->nick_name;
        }elseif(!empty($this->user_name)) {
            return $this->user_name;
        }elseif(!empty($this->phone)) {
            return $this->phone;
        }elseif(!empty($this->email)) {
            return $this->email;
        }else{
            return '匿名';
        }
    }
    
    /**
     * Verify if the provided value is a registered mobile or email
     * 
     * @param string $value could be either mobile/email
     * @return string null: not registered, 'user_name': by user_name, 'phone': by phone, 'email': by email
     */
    public static function registeredBy($value) 
    {
        $result = null;
        $customer = null;
        if ($value) {
            if (Helpers::isEmail($value)) {
                $customer = self::where('email', $value)->first();
                if ($customer) {
                    $result = 'email';
                }
            } else if (Helpers::isMobileNumber($value)) {
                $customer = self::where('phone', $value)->first();
                if ($customer) {
                    $result = 'phone';
                }
            } else {
                $customer = self::where('user_name', $value)->first();
                if ($customer) {
                    $result = 'user_name';
                }
            }
        }    
        return $result;
    }

    /**
     * Verify if customer can be login successfully
     * 
     * @param string $account The account name to login
     * @param string $password The plate password to login
     * @return stdClass/string If success return the customer record, if failed
     *         return the failed cause.
     */
    public static function login($account, $password) 
    {
        $result = null;
        $register_type = self::registeredBy($account);

        if ($register_type) {
            $customer = self::where($register_type, $account)->first();
            $md5password = Helpers::compilePassword($password, $customer->salt);

            if ($md5password == $customer->password) {
                $result = $customer;
            } else {
                $result = "密码不正确!";
            }
        } else {
            $result = "帐号不存在!";
        }   

        return $result;
    }
}
