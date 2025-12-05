<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordEmailRequest;
use App\Http\Requests\Admin\PasswordResetRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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

        // Usa o broker padrão do Laravel para gerar o token,
        // armazenar em password_reset_tokens e disparar o e-mail.
        $status = Password::sendResetLink(
            ['email' => $request->string('email')->toString()]
        );

        // Privacidade: não revelar se o e-mail existe de fato.
        return back()->with('status', __($status));
    }

    public function resetForm(Company $company, string $token): View
    {
        $email = request('email');

        return view('admin.auth.reset-password', compact('company', 'token', 'email'));
    }

    public function reset(PasswordResetRequest $request): RedirectResponse
    {
        /** @var Company $company */
        $company = $request->attributes->get('company');

        $email = $request->string('email')->toString();
        $plainToken = $request->string('reset_token')->toString();
        $newPassword = $request->string('password')->toString();

        // Localiza o usuário usando o broker padrão
        $user = Password::getUser(['email' => $email]);

        if (! $user instanceof User) {
            return back()->withErrors(['email' => __('passwords.user')]);
        }

        // Garante que é um admin ativo da empresa atual
        if (! $user->is_active || $user->role !== User::ROLE_ADMIN || (int) $user->company_id !== (int) $company->id) {
            return back()->withErrors(['email' => __('passwords.user')]);
        }

        $repository = Password::getRepository();

        // Valida token (inclui expiração interna)
        if (! $repository->exists($user, $plainToken)) {
            return back()->withErrors(['email' => __('passwords.token')]);
        }

        // Atualiza senha usando o cast "hashed"
        $user->forceFill([
            'password' => $newPassword,
            'remember_token' => Str::random(60),
        ])->save();

        // Limpa tokens desse usuário
        $repository->delete($user);

        return redirect()
            ->route('admin.login.form', ['company' => $company])
            ->with('status', __('passwords.reset'));
    }
}
