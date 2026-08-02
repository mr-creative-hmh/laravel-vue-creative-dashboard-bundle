<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse as ProfileInformationUpdatedResponseContract;

class ProfileInformationUpdatedResponse implements ProfileInformationUpdatedResponseContract
{
    public function toResponse($request)
    {
        $message = app()->getLocale() === 'ar' ? 'تم تحديث بيانات الملف الشخصي بنجاح.' : 'Profile information updated successfully.';

        return back(303)
            ->with('success', $message)
            ->with('status', 'profile-information-updated');
    }
}
