Click here to reset your password: <a href="{{ $link = action('Teach\Auth\PasswordController@showResetForm').'/'.$token.'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
