@extends('admin.layouts.guest')

@section('content')
    <div class="flex flex-col gap-3 text-center">
        <div class="mx-auto flex items-center justify-center">
            @if($company->logo_path)
                <img src="{{ Storage::disk('public')->url($company->logo_path) }}" alt="{{ $company->name }}" class="h-12 w-auto" />
            @endif
        </div>
        <div class="space-y-1">
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Definir nova senha</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Crie uma nova senha para acessar o painel.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.password.update', ['company' => $company]) }}" class="space-y-6 rounded-2xl border border-slate-200 bg-white/85 p-8 shadow-xl shadow-slate-200/60 backdrop-blur dark:border-slate-800 dark:bg-slate-900/50 dark:shadow-slate-950/40">
        @csrf
        <input type="hidden" name="reset_token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ old('email', $email) }}">

        @error('email')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nova senha</label>
            <input id="password" name="password" type="password" autocomplete="new-password" required class="w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/40 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
            @error('password')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Confirmar senha</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/40 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[var(--brand)] px-4 py-3 text-sm font-semibold text-white transition hover:opacity-90 focus:outline-none focus:ring-4 focus:ring-[color:var(--brand)]/40">Salvar nova senha</button>
        <div class="text-center text-sm">
            <a href="{{ route('admin.login.form', ['company' => $company]) }}" class="text-slate-500 hover:underline">Voltar ao login</a>
        </div>
    </form>
@endsection
