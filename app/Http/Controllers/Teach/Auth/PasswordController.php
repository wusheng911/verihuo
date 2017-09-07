<?php

namespace App\Http\Controllers\Teach\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $guard;
    protected $broker;
    protected $redirectTo;
    protected $redirectPath;
    protected $loginPath;
    protected $loginView = 'teach.auth.login';
    protected $registerView = 'teach.auth.register';
    protected $linkRequestView = 'teach.auth.passwords.email';
    protected $resetView = 'teach.auth.passwords.reset';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $admin_prefix = Config('constants.TEACHER_PREFIX');
        
        $this->guard = "teach";
        $this->broker = "teach";
        $this->redirectTo = "/{$admin_prefix}/app";
        $this->redirectPath = "/{$admin_prefix}/app";
        $this->loginPath = "/{$admin_prefix}/app/login";
      
        $this->middleware('guest');
    }

}
