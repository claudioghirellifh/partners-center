@extends('adminroot.layouts.app')

@section('header', 'Editar plano')

@section('content')
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Atualize o valor e detalhes do plano <strong>{{ $plan->name }}</strong>.</p>

    <form action="{{ route('adminroot.plans.update', $plan) }}" method="POST" class="grid gap-6 md:max-w-4xl">
        @include('adminroot.plans._form', ['plan' => $plan, 'submitLabel' => 'Salvar alterações'])
    </form>

    <div class="mt-10 rounded-xl border border-amber-300/60 bg-amber-50 px-6 py-4 text-sm text-amber-800 dark:border-amber-500/40 dark:bg-amber-900/20 dark:text-amber-200 md:max-w-4xl">
        <p class="font-semibold">Remover plano</p>
        <p class="mt-2">A exclusão remove o plano apenas no Partners Center. Se este plano existir na Iugu, exclua-o manualmente por lá também.</p>

        <form action="{{ route('adminroot.plans.destroy', $plan) }}" method="POST" class="mt-4" onsubmit="return confirm('Confirmar exclusão deste plano?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50 dark:border-red-600/60 dark:text-red-300 dark:hover:bg-red-900/20">
                Excluir plano
            </button>
        </form>
    </div>
@endsection
