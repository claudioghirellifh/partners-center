@extends('admin.layouts.app')

@section('header', 'Novo cliente')

@section('content')
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Cadastre um cliente vinculado à sua empresa para emitir cobranças.</p>

    <form action="{{ route('admin.customers.store', ['company' => $company]) }}" method="POST" class="grid gap-6">
        @include('admin.customers._form', ['company' => $company, 'submitLabel' => 'Criar cliente'])
    </form>
@endsection
