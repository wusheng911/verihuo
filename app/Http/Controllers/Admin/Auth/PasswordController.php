<?php

namespace App\Http\Controllers\Admin\Auth;

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
    protected $loginView = 'admin.auth.login';
    protected $registerView = 'admin.auth.register';
    protected $linkRequestView = 'admin.auth.passwords.email';
    protected $resetView = 'admin.auth.passwords.reset';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $admin_prefix = Config('constants.ADMIN_PREFIX');
        
        $this->guard = "admin";
        $this->broker = "admin";
        $this->redirectTo = "/{$admin_prefix}";
        $this->redirectPath = "/{$admin_prefix}";
        $this->loginPath = "/{$admin_prefix}/login";
      
        $this->middleware('guest');
    }

}
