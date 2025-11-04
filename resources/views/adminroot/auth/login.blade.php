@extends('adminroot.layouts.guest')

@section('content')
    <div class="flex flex-col gap-3 text-center">
        <div class="mx-auto flex items-center justify-center">
            <img src="{{ Vite::asset('resources/images/root/logo-light.png') }}" alt="Logo" class="h-14 w-auto dark:hidden"/>
            <img src="{{ Vite::asset('resources/images/root/logo-dark.png') }}" alt="Logo" class="hidden h-14 w-auto dark:block"/>
        </div>
        <div class="space-y-1">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Root Access</p>
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Console Partners Center</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Autentique-se com seu usuário Root para administrar empresas, admins e equipes parceiras.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ $loginAction }}" class="space-y-6 rounded-2xl border border-slate-200 bg-white/85 p-8 shadow-xl shadow-slate-200/60 backdrop-blur dark:border-slate-800 dark:bg-slate-900/50 dark:shadow-slate-950/40">
        @csrf

        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-200">E-mail</label>
            <div class="relative">
                <input
                    id="email"
                    name="email"
                    type="email"
                    inputmode="email"
                    autocomplete="username"
                    placeholder="root@empresa.com"
                    value="{{ old('email') }}"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/40 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white"
                >
            </div>
            @error('email')
                <p class="text-sm text-[#F27327]">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Senha</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                placeholder="••••••••"
                required
                class="w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/40 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white"
            >
            @error('password')
                <p class="text-sm text-[#F27327]">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 bg-white text-[#F27327] focus:ring-[#F27327]/40 dark:border-slate-700 dark:bg-slate-900">
                <span>Manter sessão iniciada</span>
            </label>
            <span class="text-xs text-slate-500 dark:text-slate-500">Somente para ambientes seguros.</span>
        </div>

        <button
            type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#F27327] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#F27327]/90 focus:outline-none focus:ring-4 focus:ring-[#F27327]/40"
        >
            Acessar painel Root
        </button>
    </form>

    <p class="text-center text-xs text-slate-500 dark:text-slate-400">
        Caminho atual: <code class="rounded bg-slate-200 px-2 py-1 text-[#F27327] dark:bg-slate-900 dark:text-[#F27327]">{{ url(config('adminroot.path')) }}</code>
    </p>
@endsection
