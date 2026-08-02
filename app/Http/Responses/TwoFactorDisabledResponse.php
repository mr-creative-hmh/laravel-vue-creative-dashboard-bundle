<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorDisabledResponse as TwoFactorDisabledResponseContract;

class TwoFactorDisabledResponse implements TwoFactorDisabledResponseContract
{
    public function toResponse($request)
    {
        $message = app()->getLocale() === 'ar' ? 'تم تعطيل المصادقة الثنائية بنجاح.' : 'Two-factor authentication disabled successfully.';

        return back(303)->with('success', $message);
    }
}
