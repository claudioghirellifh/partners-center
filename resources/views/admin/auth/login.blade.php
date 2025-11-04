@extends('admin.layouts.guest')

@section('content')
    <div class="flex flex-col gap-3 text-center">
        <div class="mx-auto flex items-center justify-center">
            @if($company->logo_path)
                <img src="{{ Storage::disk('public')->url($company->logo_path) }}" alt="{{ $company->name }}" class="h-14 w-auto" />
            @else
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--brand)]/15 text-[var(--brand)]">
                    <span class="text-xl font-semibold">{{ str($company->name)->substr(0,2)->upper() }}</span>
                </div>
            @endif
        </div>
        <div class="space-y-1">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Admin Access</p>
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $company->name }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Entre para administrar sua empresa.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.login', ['company' => $company]) }}" class="space-y-6 rounded-2xl border border-slate-200 bg-white/85 p-8 shadow-xl shadow-slate-200/60 backdrop-blur dark:border-slate-800 dark:bg-slate-900/50 dark:shadow-slate-950/40">
        @csrf

        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-200">E-mail</label>
            <input id="email" name="email" type="email" inputmode="email" autocomplete="username" placeholder="admin@empresa.com" value="{{ old('email') }}" required class="w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/40 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
            @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Senha</label>
            <input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required class="w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/40 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
            @error('password')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-between text-sm text-slate-500">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 bg-white text-[var(--brand)] focus:ring-[color:var(--brand)]/40 dark:border-slate-700 dark:bg-slate-900">
                <span>Manter sessão iniciada</span>
            </label>
            <a href="{{ route('admin.password.request', ['company' => $company]) }}" class="text-[color:var(--brand)] hover:underline">Esqueci minha senha</a>
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[var(--brand)] px-4 py-3 text-sm font-semibold text-white transition hover:opacity-90 focus:outline-none focus:ring-4 focus:ring-[color:var(--brand)]/40">Acessar painel</button>
    </form>
@endsection
