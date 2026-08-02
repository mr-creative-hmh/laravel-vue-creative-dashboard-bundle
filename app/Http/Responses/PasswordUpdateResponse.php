<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;

class PasswordUpdateResponse implements PasswordUpdateResponseContract
{
    public function toResponse($request)
    {
        $message = app()->getLocale() === 'ar' ? 'تم تحديث كلمة المرور بنجاح.' : 'Password updated successfully.';

        return back(303)->with('success', $message);
    }
}
