@extends('adminroot.layouts.app')

@section('header', 'Nova versão / aviso')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Publique uma nova versão para o painel dos admins, com notas de mudança e avisos opcionais.
        </p>
        <a href="{{ route('adminroot.release-notes.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            Voltar
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white/85 p-8 shadow-lg shadow-slate-200/60 backdrop-blur dark:border-slate-800 dark:bg-slate-900/60 dark:shadow-slate-950/30">
        <form action="{{ route('adminroot.release-notes.store') }}" method="POST" class="space-y-6">
            @include('adminroot.release-notes._form', ['submitLabel' => 'Publicar versão'])
        </form>
    </div>
@endsection
