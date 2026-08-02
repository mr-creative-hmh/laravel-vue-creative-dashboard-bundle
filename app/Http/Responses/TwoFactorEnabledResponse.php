<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorEnabledResponse as TwoFactorEnabledResponseContract;

class TwoFactorEnabledResponse implements TwoFactorEnabledResponseContract
{
    public function toResponse($request)
    {
        $message = app()->getLocale() === 'ar' ? 'تم تفعيل المصادقة الثنائية بنجاح.' : 'Two-factor authentication enabled successfully.';

        return back(303)->with('success', $message);
    }
}
