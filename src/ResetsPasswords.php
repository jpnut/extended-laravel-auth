<?php

namespace JPNut\ExtendedAuth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords as BaseResetsPasswords;
use Illuminate\Support\Facades\Hash;

trait ResetsPasswords
{
    use BaseResetsPasswords;

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->removeAllRememberTokens();

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }
}
