@extends('admin.layouts.app')

@section('header', 'Novo projeto / cliente')

@section('content')
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Cadastre um novo cliente vinculado a um plano.</p>

    <form action="{{ route('admin.projects.store', ['company' => $company]) }}" method="POST" class="grid gap-6 md:max-w-4xl">
        @include('admin.projects._form', ['plans' => $plans, 'customers' => $customers, 'company' => $company, 'submitLabel' => 'Criar projeto'])
    </form>
@endsection
