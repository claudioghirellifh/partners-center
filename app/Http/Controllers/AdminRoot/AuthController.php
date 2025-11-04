<?php

namespace App\Http\Controllers\AdminRoot;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRoot\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (Auth::guard('root')->check()) {
            return redirect()->route('adminroot.dashboard');
        }

        return $this->create($request);
    }

    public function create(Request $request): View
    {
        return view('adminroot.auth.login', [
            'routePrefix' => config('adminroot.path'),
            'loginAction' => route('adminroot.login'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = [
            'email' => $request->string('email')->toString(),
            'password' => $request->string('password')->toString(),
            'role' => User::ROLE_ROOT,
            'is_active' => true,
        ];

        $remember = $request->boolean('remember');

        if (! Auth::guard('root')->attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('Credenciais inválidas ou usuário inativo.'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('adminroot.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('root')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('adminroot.login.form');
    }
}
