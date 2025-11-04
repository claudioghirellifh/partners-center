<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }} {{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" type="image/png" sizes="32x32" href="{{ Vite::asset('resources/images/root/favicon.png') }}">
        <link rel="shortcut icon" href="{{ Vite::asset('resources/images/root/favicon.png') }}">
        @livewireStyles
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
        <div class="mx-auto flex min-h-screen w-full max-w-5xl flex-col gap-12 px-6 py-12">
            <header class="flex flex-col gap-6">
                <div class="flex items-center gap-3">
                    <img src="{{ Vite::asset('resources/images/root/logo-light.png') }}" alt="Logo" class="h-10 w-auto dark:hidden"/>
                    <img src="{{ Vite::asset('resources/images/root/logo-dark.png') }}" alt="Logo" class="hidden h-10 w-auto dark:block"/>
                </div>
                <div>
                    <span class="rounded-full bg-[#F27327]/10 px-4 py-1 text-sm font-medium text-[#F27327] ring-1 ring-[#F27327]/30">
                        Partners Center • MVP
                    </span>
                </div>
                <div class="flex flex-col gap-4">
                    <h1 class="text-4xl font-semibold tracking-tight text-white md:text-5xl">
                        Central SaaS de parceiros, canais e alianças estratégicas.
                    </h1>
                    <p class="max-w-2xl text-lg text-slate-300">
                        Uma plataforma multiempresa e multilíngue onde usuários Root criam empresas, Admins personalizam
                        marcas e equipes de parcerias aceleram integrações — cada empresa com URI dedicada e identidade própria.
                    </p>
                </div>
                <div class="flex flex-col gap-3 text-sm text-slate-300 sm:flex-row sm:items-center sm:text-base">
                    <div class="flex items-center gap-2 rounded border border-[#F27327]/50 bg-[#F27327]/10 px-4 py-2 text-[#F27327]">
                        <span class="inline-flex h-2 w-2 rounded-full bg-[#F27327]"></span>
                        Executando em <strong class="font-semibold text-white">local.partners</strong>
                    </div>
                    <div class="flex items-center gap-2 rounded border border-slate-700 px-4 py-2">
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                        Stack: Laravel 12 · Livewire 3 · Tailwind 4 · SQLite
                    </div>
                </div>
            </header>

            <main class="grid gap-6 md:grid-cols-2">
                <section class="rounded-xl border border-slate-800 bg-slate-900/60 p-6 backdrop-blur">
                    <h2 class="text-xl font-semibold text-white">Papéis e escopo</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-300">
                        <li>
                            <span class="font-medium text-white">Root:</span> acesso global, cria empresas, Admins e define padrões.
                        </li>
                        <li>
                            <span class="font-medium text-white">Admin:</span> dono da empresa, gerencia equipes parceiras e identidade visual.
                        </li>
                        <li>
                            <span class="font-medium text-white">Equipes de parceria:</span> cuidam da operação diária com parceiros e acordos comerciais.
                        </li>
                    </ul>
                </section>

                <section class="rounded-xl border border-slate-800 bg-slate-900/60 p-6 backdrop-blur">
                    <h2 class="text-xl font-semibold text-white">Multiempresa &amp; idiomas</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-300">
                        <li>URI dedicada por empresa (ex.: <code>local.partners/catus</code>).</li>
                        <li>Configuração de identidade visual: logo, favicon e duas cores principais.</li>
                        <li>Locale por empresa (<code>pt_BR</code>, <code>en</code>, <code>es</code>, ...), com fallback global configurável.</li>
                    </ul>
                </section>

                <section class="rounded-xl border border-slate-800 bg-slate-900/60 p-6 md:col-span-2">
                    <h2 class="text-xl font-semibold text-white">Próximos passos do MVP</h2>
                    <ol class="mt-4 space-y-3 text-sm text-slate-300">
                        <li>Comando Artisan para criar usuário Root via terminal (perguntas e respostas).</li>
                        <li>Resolução automática do tenant via host e roteamento multiempresa.</li>
                        <li>Painel do Admin para gestão de parceiros, identidade visual e idioma.</li>
                        <li>Fluxo das equipes de parceria para onboarding, acordos e acompanhamento de resultados.</li>
                    </ol>
                </section>
            </main>

            <footer class="mt-auto text-sm text-slate-500">
                &copy; {{ now()->year }} Partners Center — Central de Parcerias.
            </footer>
        </div>

        @livewireScripts
    </body>
</html>
