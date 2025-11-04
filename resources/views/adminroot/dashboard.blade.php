@extends('adminroot.layouts.app')

@section('header', 'Visão geral')

@section('content')
    <div class="grid gap-6 md:grid-cols-3">
        @foreach ($summaries as $summary)
            <div class="rounded-2xl border border-slate-200 bg-white/85 p-6 shadow-lg shadow-slate-200/70 backdrop-blur dark:border-slate-800 dark:bg-slate-900/60 dark:shadow-slate-950/30">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $summary['title'] }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">{{ $summary['value'] }}</p>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-500">{{ $summary['subtitle'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-10 rounded-2xl border border-slate-200 bg-white/80 p-8 shadow-inner shadow-slate-200/50 backdrop-blur dark:border-slate-800 dark:bg-slate-900/40 dark:shadow-slate-950/20">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Próximos módulos</h2>
        <p class="mt-3 max-w-2xl text-sm text-slate-500 dark:text-slate-400">
            Este painel exibirá indicadores e atalhos para criação de empresas, administração de usuários e acompanhamento
            dos Sellers. Conforme avançarmos no MVP, novas métricas e gráficos serão adicionados aqui.
        </p>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @php
                $placeholders = [
                    ['title' => 'Empresas', 'description' => 'Gerencie empresas parceiras, URIs e identidade visual.'],
                    ['title' => 'Admins', 'description' => 'Crie e atribua administradores para cada empresa.'],
                    ['title' => 'Sellers', 'description' => 'Controle o time de vendas, permissões e desempenho.'],
                ];
            @endphp
            @foreach ($placeholders as $item)
                <div class="rounded-xl border border-slate-200 bg-white/80 p-5 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-900/50 dark:text-slate-300">
                    <p class="text-base font-semibold text-slate-900 dark:text-white">{{ $item['title'] }}</p>
                    <p class="mt-2 text-slate-500 dark:text-slate-400">{{ $item['description'] }}</p>
                    <span class="mt-4 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                        Em breve
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endsection
