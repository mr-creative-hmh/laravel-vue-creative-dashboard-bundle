<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $message = app()->getLocale() === 'ar' ? 'تم تسجيل الدخول بنجاح.' : 'Logged in successfully.';

        return redirect()->intended(config('fortify.home'))->with('success', $message);
    }
}
