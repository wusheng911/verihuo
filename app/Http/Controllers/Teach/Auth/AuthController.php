<?php

namespace App\Http\Controllers\Teach\Auth;

use Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $guard; 
    protected $broker; 
    protected $redirectTo;
    protected $redirectPath;
    protected $loginPath;
    protected $loginView = 'teach.auth.login';
    protected $registerView = 'teach.auth.register';
    protected $linkRequestView = 'teach.auth.passwords.email';
    protected $resetView = 'teach.auth.passwords.reset';
    protected $redirectAfterLogout;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->guard = 'teach';
        $this->broker = 'teach';
        $this->redirectTo = '/' . Config('constants.TEACHER_PREFIX') . '/app';
        $this->redirectPath = '/' . Config('constants.TEACHER_PREFIX') . '/app';
        $this->loginPath = '/' . Config('constants.TEACHER_PREFIX') . '/login';
        $this->redirectAfterLogout = '/' . Config('constants.TEACHER_PREFIX') . '/login';
        
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
           'name' => 'required|max:255',
           'email' => 'required|email|max:255|unique:users',
           'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return Teacher::create([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'password' => bcrypt($data['password']),
                            ]);
    }

    public function logininfo()
    {
        $teacher = Auth::guard('teach')->user();
        $json = ['teacher'=>$teacher];
        return response()->json($json);
    }

}
