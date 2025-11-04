<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('web');

        /** @var \App\Models\Company|null $company */
        $company = $request->attributes->get('company');

        if (! $company || ! $company->is_active) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403, 'Empresa suspensa.');
        }

        if (! $guard->check()) {
            return redirect()->route('admin.login.form', ['company' => $company]);
        }

        /** @var User $user */
        $user = $guard->user();

        if ((int) $user->company_id !== (int) $company->id || $user->role !== User::ROLE_ADMIN || ! $user->is_active) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login.form', ['company' => $company])->withErrors([
                'email' => 'Sessão inválida. Faça login novamente.',
            ]);
        }

        return $next($request);
    }
}
