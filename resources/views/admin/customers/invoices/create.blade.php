@extends('admin.layouts.app')

@section('header', 'Nova cobrança avulsa')

@section('content')
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Gere uma fatura única na Iugu para o cliente <strong>{{ $customer->name }}</strong>.</p>

    <form action="{{ route('admin.customers.charges.store', ['company' => $company, 'customer' => $customer]) }}" method="POST" class="grid gap-6 md:max-w-3xl">
        @csrf
        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Descrição</label>
                <input type="text" name="description" value="{{ old('description') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Valor</label>
                <input type="text" name="amount" value="{{ old('amount') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white" placeholder="R$ 0,00">
                @error('amount')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Vencimento</label>
                <input type="date" name="due_date" value="{{ old('due_date', now()->toDateString()) }}" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                @error('due_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Notas internas</label>
            <textarea name="notes" rows="4" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">{{ old('notes') }}</textarea>
            @error('notes')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Mensagem ao cliente (e-mail)</label>
            <textarea name="email_message" rows="4" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white" placeholder="Escreva uma mensagem para acompanhar o link de pagamento.">{{ old('email_message') }}</textarea>
            @error('email_message')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[color:var(--brand)] px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">Gerar fatura</button>
            <button type="submit" name="send_test" value="1" class="inline-flex items-center gap-2 rounded-lg border border-emerald-300 bg-emerald-50 px-5 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100 dark:border-emerald-600/70 dark:bg-emerald-900/20 dark:text-emerald-300 dark:hover:bg-emerald-900/30">Enviar e-mail teste</button>
            <a href="{{ route('admin.customers.index', ['company' => $company]) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancelar</a>
        </div>
    </form>
@endsection
