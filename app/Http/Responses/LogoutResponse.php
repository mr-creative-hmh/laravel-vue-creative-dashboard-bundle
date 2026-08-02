<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        $message = app()->getLocale() === 'ar' ? 'تم تسجيل الخروج بنجاح.' : 'Logged out successfully.';

        return redirect('/')->with('success', $message);
    }
}
