@extends('adminroot.layouts.app')

@section('header', 'Integrações')

@section('content')
    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-xl border border-slate-200 bg-white/80 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Iugu</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Conecte sua conta Iugu para emissão de cobranças e assinaturas.</p>
                </div>
            </div>

            @if (session('status'))
                <div class="mt-4 rounded-lg border border-emerald-300/40 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-400/30 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('adminroot.integrations.iugu.update') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">API Token</label>
                    <input type="text" name="api_token" value="{{ old('api_token', $iuguToken) }}" placeholder="sk_live_..." class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    @error('api_token')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <p class="text-xs text-slate-400 dark:text-slate-500">
                    Dica: use um token de API com permissões adequadas para assinaturas e faturas. Você pode gerenciá-los na dashboard da Iugu.
                </p>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">
                        Salvar token
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
