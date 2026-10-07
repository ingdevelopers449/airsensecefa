<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $roleCode = $user->role ? $user->role->code : null;
        $roleId = $user->role_id;

        if ($roleCode === 'ADMIN' || $roleId == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($roleCode === 'SST' || $roleId == 2) {
            return redirect()->route('ehscefa.dashboard');
        } elseif ($roleCode === 'INSTRUCTOR' || $roleId == 3) {
            return redirect()->route('instructor.dashboard');
        }

        return redirect()->intended('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
