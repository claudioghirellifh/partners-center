@extends('adminroot.layouts.app')

@section('header', 'Editar plano')

@section('content')
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Atualize os valores e condições do plano <strong>{{ $plan->name }}</strong>.</p>

    <form action="{{ route('adminroot.plans.update', $plan) }}" method="POST" class="grid gap-6 md:max-w-4xl">
        @include('adminroot.plans._form', ['plan' => $plan, 'submitLabel' => 'Salvar alterações'])
    </form>
@endsection
