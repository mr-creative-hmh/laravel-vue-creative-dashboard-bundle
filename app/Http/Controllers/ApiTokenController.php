<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApiTokenController extends Controller
{
    /**
     * Display listing of user API tokens.
     */
    public function index(Request $request): Response
    {
        $tokens = $request->user()->tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'abilities' => $token->abilities,
                'last_used_at' => $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never',
                'created_at' => $token->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return Inertia::render('settings/ApiTokens', [
            'tokens' => $tokens,
        ]);
    }

    /**
     * Create a new API token.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'nullable|array',
        ]);

        $abilities = $validated['abilities'] ?? ['*'];
        $token = $request->user()->createToken($validated['name'], $abilities);

        activity()
            ->causedBy($request->user())
            ->log("Generated API Token '{$validated['name']}'");

        return back()->with([
            'success' => 'API Token generated successfully.',
            'plainTextToken' => $token->plainTextToken,
        ]);
    }

    /**
     * Delete/Revoke an API token.
     */
    public function destroy(Request $request, int $tokenId): RedirectResponse
    {
        $token = $request->user()->tokens()->where('id', $tokenId)->firstOrFail();
        $tokenName = $token->name;
        $token->delete();

        activity()
            ->causedBy($request->user())
            ->log("Revoked API Token '{$tokenName}'");

        return back()->with('success', 'API token revoked.');
    }
}
