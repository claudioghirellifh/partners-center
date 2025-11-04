<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
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
        /** @var \App\Models\Company|null $company */
        $company = $request->attributes->get('company');

        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard', ['company' => $company]);
        }

        return $this->create($request);
    }

    public function create(Request $request): View
    {
        /** @var \App\Models\Company $company */
        $company = $request->attributes->get('company');

        return view('admin.auth.login', compact('company'));
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        /** @var \App\Models\Company $company */
        $company = $request->attributes->get('company');

        $credentials = [
            'email' => $request->string('email')->toString(),
            'password' => $request->string('password')->toString(),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'company_id' => $company->id,
        ];

        $remember = $request->boolean('remember');

        if (! Auth::guard('web')->attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('Credenciais inválidas.'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard', ['company' => $company]));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        /** @var \App\Models\Company|null $company */
        $company = $request->attributes->get('company');

        return redirect()->route('admin.login.form', ['company' => $company]);
    }
}
