@extends('admin.layouts.app')

@section('header', 'Faturas do cliente')

@section('content')
    <div class="mb-6">
        <p class="text-sm text-slate-500 dark:text-slate-400">Mostrando últimas faturas sincronizadas diretamente da Iugu para <strong>{{ $customer->name }}</strong>.</p>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white/80 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
        <table class="min-w-full text-left text-sm text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-100/80 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-900/40 dark:text-slate-400">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Valor</th>
                    <th class="px-4 py-3">Vencimento</th>
                    <th class="px-4 py-3">Pagamento</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr class="border-t border-slate-200/80 dark:border-slate-800/80">
                        <td class="px-4 py-3 font-mono text-xs">{{ $invoice['id'] ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ ($invoice['status'] ?? '') === 'paid' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-200' }}">
                                {{ strtoupper($invoice['status'] ?? 'desconhecido') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">R$ {{ number_format(($invoice['total_cents'] ?? 0) / 100, 2, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $invoice['due_date'] ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $invoice['paid_at'] ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">Nenhuma fatura encontrada para este cliente.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.customers.index', ['company' => $company]) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Voltar para clientes</a>
    </div>
@endsection
