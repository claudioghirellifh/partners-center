@extends('admin.layouts.app')

@section('header', 'Clientes')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">Gerencie clientes que receberão cobranças e comunicações.</p>
        <a href="{{ route('admin.customers.create', ['company' => $company]) }}" class="inline-flex items-center gap-2 rounded-lg bg-[color:var(--brand)] px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90">Novo cliente</a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-300/40 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-400/30 dark:bg-emerald-900/20 dark:text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->has('customer'))
        <div class="mb-6 rounded-lg border border-red-300/40 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-400/30 dark:bg-red-900/20 dark:text-red-200">
            {{ $errors->first('customer') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white/80 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
        <table class="min-w-full text-left text-sm text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-100/80 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-900/40 dark:text-slate-400">
                <tr>
                    <th class="px-4 py-3">Nome</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Telefone</th>
                    <th class="px-4 py-3">Documento</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr class="border-t border-slate-200/80 dark:border-slate-800/80">
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $customer->name }}</td>
                        <td class="px-4 py-3">{{ $customer->email }}</td>
                        <td class="px-4 py-3">
                            @php
                                $formattedPhone = null;
                                if ($customer->phone) {
                                    $digits = preg_replace('/\D+/', '', $customer->phone);
                                    if (strlen($digits) === 11) {
                                        $formattedPhone = sprintf('(%s) %s-%s', substr($digits, 0, 2), substr($digits, 2, 5), substr($digits, 7));
                                    } elseif (strlen($digits) === 10) {
                                        $formattedPhone = sprintf('(%s) %s-%s', substr($digits, 0, 2), substr($digits, 2, 4), substr($digits, 6));
                                    } else {
                                        $formattedPhone = $digits;
                                    }
                                }
                            @endphp
                            {{ $formattedPhone ?? '—' }}
                        </td>
                        <td class="px-4 py-3">{{ $customer->cpf_cnpj }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.customers.edit', ['company' => $company, 'customer' => $customer]) }}" class="inline-flex items-center gap-1 rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">Editar</a>
                            <a href="{{ route('admin.customers.invoices', ['company' => $company, 'customer' => $customer]) }}" class="inline-flex items-center gap-1 rounded-md border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-50 dark:border-blue-600/70 dark:text-blue-300 dark:hover:bg-blue-900/20">Faturas</a>
                            <form action="{{ route('admin.customers.destroy', ['company' => $company, 'customer' => $customer]) }}" method="POST" class="inline" onsubmit="return confirm('Remover cliente? Esta ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 rounded-md border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50 dark:border-red-600/70 dark:text-red-300 dark:hover:bg-red-900/20">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">Nenhum cliente cadastrado ainda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $customers->links() }}</div>
@endsection
