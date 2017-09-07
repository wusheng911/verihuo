Click here to reset your password: <a href="{{ $link = action('Admin\Auth\PasswordController@showResetForm').'/'.$token.'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
