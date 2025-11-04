<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRootUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('root');

        if (! $guard->check()) {
            return redirect()->route('adminroot.login.form');
        }

        /** @var User $user */
        $user = $guard->user();

        if (! $user->isRoot() || ! $user->is_active) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('adminroot.login.form')
                ->withErrors([
                    'email' => 'Sessão encerrada. Faça login novamente.',
                ]);
        }

        return $next($request);
    }
}
