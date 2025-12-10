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
@inject('releaseNotes', 'App\Repositories\ReleaseNoteRepository')
@php
    $visibleReleaseNotes = $releaseNotes->latest();
    $currentReleaseNote = $visibleReleaseNotes->first();
@endphp
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
                        @php
                            $impersonatedCompany = $impersonation->impersonatedCompany();
                        @endphp
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
                            @if($currentReleaseNote)
                                <button
                                    type="button"
                                    data-release-toggle
                                    data-release-version="{{ $currentReleaseNote->version }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white/70 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-[color:var(--brand)] hover:text-[color:var(--brand)] dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-200 dark:hover:border-[color:var(--brand)] cursor-pointer"
                                >
                                    <span class="text-sm font-semibold text-slate-900 dark:text-white">v{{ $currentReleaseNote->version }}</span>
                                    <span data-release-unread class="hidden h-2.5 w-2.5 rounded-full bg-red-500 shadow-[0_0_0_4px] shadow-red-500/40 animate-pulse"></span>
                                </button>
                            @endif
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
        @if($visibleReleaseNotes->isNotEmpty())
            @php
                $headerTimestamp = ($currentReleaseNote?->published_at ?? $currentReleaseNote?->updated_at ?? $visibleReleaseNotes->first()->published_at ?? $visibleReleaseNotes->first()->updated_at);
                $alertStyles = [
                    'warning' => 'bg-amber-50 text-amber-800 ring-amber-200 dark:bg-amber-900/30 dark:text-amber-100 dark:ring-amber-800/60',
                    'critical' => 'bg-red-50 text-red-800 ring-red-200 dark:bg-red-900/30 dark:text-red-100 dark:ring-red-800/60',
                    'info' => 'bg-blue-50 text-blue-800 ring-blue-200 dark:bg-blue-900/30 dark:text-blue-100 dark:ring-blue-800/60',
                ];
            @endphp
            <div id="release-modal" class="fixed inset-0 z-40 hidden items-start justify-center bg-slate-900/70 px-4 py-10 backdrop-blur">
                <div class="relative w-full max-w-3xl rounded-2xl border border-slate-200 bg-white/95 shadow-2xl shadow-slate-900/30 ring-1 ring-slate-200 dark:border-slate-800 dark:bg-slate-900/95 dark:ring-slate-800/80">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Notas de versão</p>
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                                Versão {{ $currentReleaseNote?->version ?? $visibleReleaseNotes->first()->version }}
                            </h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                Última atualização em {{ $headerTimestamp?->format('d/m/Y H:i') ?? '—' }}
                            </p>
                        </div>
                        <button
                            type="button"
                            data-release-close
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-500 transition hover:border-[color:var(--brand)] hover:text-[color:var(--brand)] dark:border-slate-700 dark:text-slate-300"
                        >
                            Fechar
                        </button>
                    </div>
                    <div class="max-h-[70vh] space-y-4 overflow-y-auto px-6 py-5">
                        @foreach($visibleReleaseNotes as $releaseNote)
                            @php
                                $noteTimestamp = $releaseNote->published_at ?? $releaseNote->updated_at;
                            @endphp
                            <div class="rounded-xl border border-slate-200 bg-white/90 p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                            Versão {{ $releaseNote->version }} • {{ $noteTimestamp?->format('d/m/Y H:i') ?? 'Sem data' }}
                                        </p>
                                        <p class="mt-1 text-base font-semibold text-slate-900 dark:text-white">{{ $releaseNote->title ?? 'Atualizações' }}</p>
                                    </div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if($releaseNote->is_current)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-100 dark:ring-emerald-800/60">Atual</span>
                                            @endif
                                            @if($releaseNote->hasAlert())
                                                @php
                                                    $variant = $alertStyles[$releaseNote->alert_level ?? 'info'] ?? $alertStyles['info'];
                                                @endphp
                                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-semibold ring-1 {{ $variant }}">Aviso</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($releaseNote->hasAlert())
                                        @php
                                            $variant = $alertStyles[$releaseNote->alert_level ?? 'info'] ?? $alertStyles['info'];
                                        @endphp
                                        <div class="mt-3 rounded-lg px-3 py-2 text-sm ring-1 {{ $variant }}">
                                            {{ $releaseNote->alert_message }}
                                        </div>
                                    @endif
                                    @php
                                        $notesList = $releaseNote->notesAsList();
                                    @endphp
                                    @if(!empty($notesList))
                                        <ul class="mt-3 space-y-2 text-sm text-slate-700 dark:text-slate-200">
                                            @foreach($notesList as $item)
                                                <li class="flex items-start gap-2">
                                                    <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[color:var(--brand)]"></span>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif($releaseNote->notes)
                                    <p class="mt-3 text-sm text-slate-700 dark:text-slate-200">{{ $releaseNote->notes }}</p>
                                @else
                                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Nenhuma nota adicionada para esta versão.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <script>
                (function initReleaseModal() {
                    const mount = () => {
                        const modal = document.getElementById('release-modal');
                        const trigger = document.querySelector('[data-release-toggle]');

                        if (!modal || !trigger) {
                            return;
                        }

                        const unreadBadge = trigger.querySelector('[data-release-unread]');
                        const currentVersion = trigger.dataset.releaseVersion || '';
                        const storageKey = `release_note_read_company_{{ $company->id }}`;

                        const markAsRead = () => {
                            try {
                                localStorage.setItem(storageKey, currentVersion);
                                if (unreadBadge) {
                                    unreadBadge.classList.add('hidden');
                                }
                            } catch (e) {
                                console.warn('Não foi possível marcar release como lida.', e);
                            }
                        };

                        const checkUnread = () => {
                            try {
                                const stored = localStorage.getItem(storageKey);
                                if (stored !== currentVersion && unreadBadge) {
                                    unreadBadge.classList.remove('hidden');
                                }
                            } catch (e) {
                                // silencioso
                            }
                        };

                        checkUnread();

                        const closeButtons = modal.querySelectorAll('[data-release-close]');

                        const openModal = () => {
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                            modal.setAttribute('aria-hidden', 'false');
                            document.body.classList.add('overflow-hidden');
                            markAsRead();
                        };

                        const closeModal = () => {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                            modal.setAttribute('aria-hidden', 'true');
                            document.body.classList.remove('overflow-hidden');
                        };

                        trigger.addEventListener('click', (event) => {
                            event.preventDefault();
                            openModal();
                        });

                        closeButtons.forEach((button) => {
                            button.addEventListener('click', closeModal);
                        });

                        modal.addEventListener('click', (event) => {
                            if (event.target === modal) {
                                closeModal();
                            }
                        });

                        document.addEventListener('keydown', (event) => {
                            if (event.key === 'Escape' && ! modal.classList.contains('hidden')) {
                                closeModal();
                            }
                        });
                    };

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', mount, { once: true });
                    } else {
                        mount();
                    }
                })();
            </script>
        @endif
        @livewireStyles
        @livewireScripts
    </body>
    </html>
