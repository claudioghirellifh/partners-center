<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full {{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }} · Root</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" type="image/png" sizes="32x32" href="{{ Vite::asset('resources/images/root/favicon.png') }}">
        <link rel="shortcut icon" href="{{ Vite::asset('resources/images/root/favicon.png') }}">
        @livewireStyles
    </head>
    <body class="min-h-full bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
        <div class="flex min-h-screen">
            <aside class="hidden w-72 flex-col border-r border-slate-200 bg-white/80 backdrop-blur dark:border-slate-800 dark:bg-slate-900/60 lg:flex">
                <div class="flex h-20 items-center gap-3 px-8">
                    <div class="flex h-10 w-10 items-center justify-center">
                        <img src="{{ Vite::asset('resources/images/root/logo-light.png') }}" alt="Logo" class="h-10 w-auto dark:hidden"/>
                        <img src="{{ Vite::asset('resources/images/root/logo-dark.png') }}" alt="Logo" class="hidden h-10 w-auto dark:block"/>
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-wide text-slate-500 dark:text-slate-400">Root Console</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ config('app.name') }}</p>
                    </div>
                </div>
                <nav class="flex-1 px-4 py-4">
                    <p class="px-4 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        Navegação
                    </p>
                    <ul class="mt-4 space-y-1 text-sm">
                        <li>
                            <a
                                href="{{ route('adminroot.dashboard') }}"
                                class="flex items-center gap-3 rounded-lg px-4 py-2 font-medium text-slate-600 transition hover:bg-slate-200/70 dark:text-slate-300 dark:hover:bg-slate-800/60 {{ request()->routeIs('adminroot.dashboard') ? 'bg-slate-200 text-slate-900 dark:bg-slate-800/80 dark:text-white' : '' }}"
                            >
                                <span class="inline-flex h-2 w-2 rounded-full {{ request()->routeIs('adminroot.dashboard') ? 'bg-[#F27327]' : 'bg-slate-400 dark:bg-slate-600' }}"></span>
                                Visão geral
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ route('adminroot.companies.index') }}"
                                class="flex items-center gap-3 rounded-lg px-4 py-2 font-medium text-slate-600 transition hover:bg-slate-200/70 dark:text-slate-300 dark:hover:bg-slate-800/60 {{ request()->routeIs('adminroot.companies.*') ? 'bg-slate-200 text-slate-900 dark:bg-slate-800/80 dark:text-white' : '' }}"
                            >
                                <span class="inline-flex h-2 w-2 rounded-full {{ request()->routeIs('adminroot.companies.*') ? 'bg-[#F27327]' : 'bg-slate-400 dark:bg-slate-600' }}"></span>
                                Empresas
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ route('adminroot.users.index') }}"
                                class="flex items-center gap-3 rounded-lg px-4 py-2 font-medium text-slate-600 transition hover:bg-slate-200/70 dark:text-slate-300 dark:hover:bg-slate-800/60 {{ request()->routeIs('adminroot.users.*') ? 'bg-slate-200 text-slate-900 dark:bg-slate-800/80 dark:text-white' : '' }}"
                            >
                                <span class="inline-flex h-2 w-2 rounded-full {{ request()->routeIs('adminroot.users.*') ? 'bg-[#F27327]' : 'bg-slate-400 dark:bg-slate-600' }}"></span>
                                Usuários
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ route('adminroot.plans.index') }}"
                                class="flex items-center gap-3 rounded-lg px-4 py-2 font-medium text-slate-600 transition hover:bg-slate-200/70 dark:text-slate-300 dark:hover:bg-slate-800/60 {{ request()->routeIs('adminroot.plans.*') ? 'bg-slate-200 text-slate-900 dark:bg-slate-800/80 dark:text-white' : '' }}"
                            >
                                <span class="inline-flex h-2 w-2 rounded-full {{ request()->routeIs('adminroot.plans.*') ? 'bg-[#F27327]' : 'bg-slate-400 dark:bg-slate-600' }}"></span>
                                Planos
                            </a>
                        </li>
                        <li>
                            <button
                                type="button"
                                class="flex w-full items-center gap-3 rounded-lg px-4 py-2 text-left font-medium text-slate-400 transition hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300"
                                disabled
                            >
                                <span class="inline-flex h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                Módulos futuros
                            </button>
                        </li>
                    </ul>
                </nav>
                <div class="border-t border-slate-200 px-6 py-4 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-500">
                    Sessão iniciada como <span class="font-medium text-slate-700 dark:text-slate-300">{{ auth('root')->user()?->email }}</span>
                </div>
            </aside>

            <div class="flex flex-1 flex-col">
                <header class="border-b border-slate-200 bg-white/70 backdrop-blur dark:border-slate-800 dark:bg-slate-900/40">
                    <div class="flex h-20 items-center justify-between px-6">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Painel Root
                            </span>
                            <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">
                                @yield('header', 'Visão geral')
                            </h1>
                        </div>
                        <div class="flex items-center gap-3">
                            @livewire('theme-toggle')

                            <form method="POST" action="{{ route('adminroot.logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-lg border border-[#F27327]/60 bg-[#F27327]/10 px-4 py-2 text-sm font-medium text-[#F27327] transition hover:bg-[#F27327]/20 hover:text-white dark:border-[#F27327]/60 dark:bg-[#F27327]/10 dark:text-[#F27327] dark:hover:text-white"
                                >
                                    <span>Encerrar sessão</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="flex-1 bg-slate-100/70 dark:bg-slate-950">
                    <div class="mx-auto w-full max-w-6xl px-6 py-10">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
