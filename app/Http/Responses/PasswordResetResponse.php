<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;

class PasswordResetResponse implements PasswordResetResponseContract
{
    public function toResponse($request)
    {
        $message = app()->getLocale() === 'ar' ? 'تم إعادة تعيين كلمة المرور بنجاح.' : 'Password reset successfully.';

        return redirect('/login')->with('success', $message);
    }
}
