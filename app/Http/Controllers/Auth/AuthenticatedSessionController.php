<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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
     public function store(Request $request): RedirectResponse
{
    // Validación de credenciales
    $request->validate([
        'email'    => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    // Recordarme
    $remember = $request->boolean('remember');

    // Intento de autenticación
    if (! Auth::attempt($request->only('email', 'password'), $remember)) {
        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    // Proteger contra session fixation
    $request->session()->regenerate();

    // Redirige a la URL pretendida o al dashboard
    return redirect()->intended(route('dashboard'));
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
