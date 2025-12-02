@extends('adminroot.layouts.app')

@section('header', 'E-mails transacionais')

@section('content')
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Consulte modelos de e-mails enviados automaticamente pela plataforma.</p>

    <div class="grid gap-4 md:grid-cols-2">
        @forelse($templates as $template)
            <div class="rounded-2xl border border-slate-200 bg-white/80 p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/60">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Template</p>
                <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $template['name'] }}</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $template['description'] }}</p>
                <a href="{{ route('adminroot.emails.show', ['template' => $template['key']]) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 rounded-full border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Ver modelo</a>
            </div>
        @empty
            <p class="text-sm text-slate-500 dark:text-slate-400">Nenhum template cadastrado.</p>
        @endforelse
    </div>
@endsection
