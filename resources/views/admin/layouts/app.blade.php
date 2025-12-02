<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full {{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $company->name }} · Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @if($company->favicon_path)
            <link rel="icon" type="image/png" href="{{ Storage::disk('public')->url($company->favicon_path) }}">
        @endif
    </head>
@inject('impersonation', 'App\Services\Impersonation\ImpersonationManager')
<body class="min-h-full bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100" style="--brand: {{ $company->brand_color ?? '#F27327' }};">
        <div class="flex min-h-screen">
            <aside class="hidden w-72 flex-col border-r border-slate-200 bg-white/80 backdrop-blur dark:border-slate-800 dark:bg-slate-900/60 lg:flex">
                <div class="flex h-20 items-center gap-3 px-8">
                    <div class="flex h-10 w-10 items-center justify-center">
                        @if($company->logo_path)
                            <img src="{{ Storage::disk('public')->url($company->logo_path) }}" alt="{{ $company->name }}" class="h-10 w-auto" />
                        @endif
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-wide text-slate-500 dark:text-slate-400">Admin Console</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $company->name }}</p>
                    </div>
                </div>
                <nav class="flex-1 px-4 py-4">
                    <p class="px-4 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Navegação</p>
                    <ul class="mt-4 space-y-1 text-sm">
                        <li>
                            <a href="{{ route('admin.dashboard', ['company' => $company]) }}" class="flex items-center gap-3 rounded-lg px-4 py-2 font-medium text-slate-600 transition hover:bg-slate-200/70 dark:text-slate-300 dark:hover:bg-slate-800/60 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-200 text-slate-900 dark:bg-slate-800/80 dark:text-white' : ''}}">
                                <span class="inline-flex h-2 w-2 rounded-full {{ request()->routeIs('admin.dashboard') ? 'bg-[var(--brand)]' : 'bg-slate-400 dark:bg-slate-600' }}"></span>
                                Visão geral
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.admins.index', ['company' => $company]) }}" class="flex items-center gap-3 rounded-lg px-4 py-2 font-medium text-slate-600 transition hover:bg-slate-200/70 dark:text-slate-300 dark:hover:bg-slate-800/60 {{ request()->routeIs('admin.admins.*') ? 'bg-slate-200 text-slate-900 dark:bg-slate-800/80 dark:text-white' : ''}}">
                                <span class="inline-flex h-2 w-2 rounded-full {{ request()->routeIs('admin.admins.*') ? 'bg-[var(--brand)]' : 'bg-slate-400 dark:bg-slate-600' }}"></span>
                                Administradores
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.projects.index', ['company' => $company]) }}" class="flex items-center gap-3 rounded-lg px-4 py-2 font-medium text-slate-600 transition hover:bg-slate-200/70 dark:text-slate-300 dark:hover:bg-slate-800/60 {{ request()->routeIs('admin.projects.*') ? 'bg-slate-200 text-slate-900 dark:bg-slate-800/80 dark:text-white' : ''}}">
                                <span class="inline-flex h-2 w-2 rounded-full {{ request()->routeIs('admin.projects.*') ? 'bg-[var(--brand)]' : 'bg-slate-400 dark:bg-slate-600' }}"></span>
                                Projetos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.customers.index', ['company' => $company]) }}" class="flex items-center gap-3 rounded-lg px-4 py-2 font-medium text-slate-600 transition hover:bg-slate-200/70 dark:text-slate-300 dark:hover:bg-slate-800/60 {{ request()->routeIs('admin.customers.*') ? 'bg-slate-200 text-slate-900 dark:bg-slate-800/80 dark:text-white' : ''}}">
                                <span class="inline-flex h-2 w-2 rounded-full {{ request()->routeIs('admin.customers.*') ? 'bg-[var(--brand)]' : 'bg-slate-400 dark:bg-slate-600' }}"></span>
                                Clientes
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            <div class="flex flex-1 flex-col">
                <header class="border-b border-slate-200 bg-white/70 backdrop-blur dark:border-slate-800 dark:bg-slate-900/40">
                    @if ($impersonation->isImpersonating())
                        @php($impersonatedCompany = $impersonation->impersonatedCompany())
                        <div class="border-b border-amber-400/40 bg-amber-100 px-6 py-3 text-sm text-amber-800 dark:border-amber-500/40 dark:bg-amber-900/20 dark:text-amber-200">
                            <div class="flex items-center justify-between gap-3">
                                <span>
                                    Você está operando como <strong>{{ $impersonatedCompany?->name ?? 'empresa' }}</strong>.
                                </span>
                                <form action="{{ route('adminroot.impersonation.leave') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-md border border-amber-500/40 bg-amber-500/20 px-3 py-1.5 text-xs font-semibold text-amber-800 transition hover:bg-amber-500/30 dark:text-amber-200">
                                        Voltar ao painel Root
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                    <div class="flex h-20 items-center justify-between px-6">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Painel Admin</span>
                            <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">@yield('header', 'Visão geral')</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            @livewire('theme-toggle')
                            <form method="POST" action="{{ route('admin.logout', ['company' => $company]) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-[color:var(--brand)]/60 bg-[color:var(--brand)]/10 px-4 py-2 text-sm font-medium text-[var(--brand)] transition hover:bg-[color:var(--brand)]/20 hover:text-white">Sair</button>
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
        @livewireStyles
        @livewireScripts
    </body>
    </html>
