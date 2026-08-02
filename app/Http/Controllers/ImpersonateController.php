<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ImpersonateController extends Controller
{
    /**
     * Start impersonating a user.
     */
    public function take(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('users.edit');

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        $originalUserId = $request->user()->id;

        Auth::login($user);
        $request->session()->put('impersonator_id', $originalUserId);

        activity()
            ->causedBy(User::find($originalUserId))
            ->performedOn($user)
            ->log("Started impersonating user '{$user->email}'");

        return redirect()->route('dashboard')->with('success', "You are now impersonating {$user->name}.");
    }

    /**
     * Leave impersonation and return to original user.
     */
    public function leave(Request $request): RedirectResponse
    {
        if (! $request->session()->has('impersonator_id')) {
            return redirect()->route('dashboard');
        }

        $originalUserId = $request->session()->get('impersonator_id');
        $originalUser = User::findOrFail($originalUserId);

        Auth::login($originalUser);
        $request->session()->forget('impersonator_id');

        activity()
            ->causedBy($originalUser)
            ->log('Stopped impersonation session.');

        return redirect()->route('users.index')->with('success', 'Exited impersonation session.');
    }
}
