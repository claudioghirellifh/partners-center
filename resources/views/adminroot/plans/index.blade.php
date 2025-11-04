@extends('adminroot.layouts.app')

@section('header', 'Planos')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-500 dark:text-slate-400">Defina os planos e valores oferecidos para as empresas.</p>
        <div class="flex items-center gap-2">
            <form action="{{ route('adminroot.plans.sync') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-[#F27327]/60 bg-[#F27327]/10 px-4 py-2 text-sm font-semibold text-[#F27327] transition hover:bg-[#F27327]/20 hover:text-white">
                    Sincronizar planos
                </button>
            </form>
            <a href="{{ route('adminroot.plans.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">Novo plano</a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg border border-amber-300/60 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-500/40 dark:bg-amber-900/20 dark:text-amber-200">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->has('plan'))
        <div class="mb-6 rounded-lg border border-red-300/60 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-600/50 dark:bg-red-900/20 dark:text-red-200">
            {{ $errors->first('plan') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white/80 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
        <table class="min-w-full text-left text-sm text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-100/80 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-900/40 dark:text-slate-400">
                <tr>
                    <th class="px-4 py-3">Nome</th>
                    <th class="px-4 py-3">Plan ID</th>
                    <th class="px-4 py-3">Preço mensal</th>
                    <th class="px-4 py-3">Preço anual</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($plans as $plan)
                    <tr class="border-t border-slate-200/80 dark:border-slate-800/80">
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $plan->name }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $plan->plan_id ?? '—' }}</td>
                        <td class="px-4 py-3">R$ {{ number_format($plan->monthly_price, 2, ',', '.') }}</td>
                        <td class="px-4 py-3">R$ {{ number_format($plan->annual_price, 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('adminroot.plans.edit', $plan) }}" class="inline-flex items-center gap-1 rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">Editar</a>
                            <form action="{{ route('adminroot.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Remover plano? Esta ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 rounded-md border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50 dark:border-red-600/70 dark:text-red-300 dark:hover:bg-red-900/20">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">Nenhum plano cadastrado ainda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $plans->links() }}</div>
@endsection
