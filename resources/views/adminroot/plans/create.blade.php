@extends('adminroot.layouts.app')

@section('header', 'Novo plano')

@section('content')
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Cadastre um novo plano e defina valores mensais e anuais.</p>

    <form action="{{ route('adminroot.plans.store') }}" method="POST" class="grid gap-6 md:max-w-4xl">
        @include('adminroot.plans._form', ['submitLabel' => 'Criar plano'])
    </form>
@endsection
