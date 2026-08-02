<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        $message = app()->getLocale() === 'ar' ? 'تم إنشاء الحساب بنجاح.' : 'Account created successfully.';

        return redirect(config('fortify.home'))->with('success', $message);
    }
}
