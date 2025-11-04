@extends('admin.layouts.app')

@section('header', 'Visão geral')

@section('content')
    <div class="rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-lg shadow-slate-200/60 backdrop-blur dark:border-slate-800 dark:bg-slate-900/60 dark:shadow-slate-950/30">
        <p class="text-sm text-slate-500">Bem-vindo(a), {{ auth()->user()?->name }}.</p>
        <p class="mt-2 text-slate-700 dark:text-slate-300">Este é o painel da empresa <span class="font-semibold">{{ $company->name }}</span>.</p>
    </div>
@endsection

