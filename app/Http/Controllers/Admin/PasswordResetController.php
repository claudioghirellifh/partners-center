<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordEmailRequest;
use App\Http\Requests\Admin\PasswordResetRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function request(): View
    {
        /** @var Company $company */
        $company = request()->attributes->get('company');
        return view('admin.auth.forgot-password', compact('company'));
    }

    public function email(PasswordEmailRequest $request): RedirectResponse
    {
        /** @var Company $company */
        $company = $request->attributes->get('company');

        $status = Password::sendResetLink(
            ['email' => $request->string('email')->toString()]
        );

        // Privacidade: não revelar se o e-mail existe; mas podemos bloquear usuários não-admin na etapa de reset
        return back()->with('status', __($status));
    }

    public function resetForm(string $token): View
    {
        /** @var Company $company */
        $company = request()->attributes->get('company');
        $email = request('email');
        return view('admin.auth.reset-password', compact('company', 'token', 'email'));
    }

    public function reset(PasswordResetRequest $request): RedirectResponse
    {
        /** @var Company $company */
        $company = $request->attributes->get('company');

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($company) {
                // Restringe a admins da empresa e ativos
                if (! $user->is_active || $user->role !== User::ROLE_ADMIN || (int) $user->company_id !== (int) $company->id) {
                    return;
                }

                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login.form', ['company' => $company])->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
