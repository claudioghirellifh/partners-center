@extends('adminroot.layouts.app')

@section('header', 'Empresas')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500">Gerencie as empresas cadastradas na plataforma.</p>
        <a href="{{ route('adminroot.companies.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">Nova empresa</a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-300/40 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-400/30 dark:bg-emerald-900/20 dark:text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white/80 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
        <table class="min-w-full text-left text-sm text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-100/80 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-900/40 dark:text-slate-400">
                <tr>
                    <th class="px-4 py-3">Nome</th>
                    <th class="px-4 py-3">URI</th>
                    <th class="px-4 py-3">Idioma</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $company)
                    <tr class="border-t border-slate-200/80 dark:border-slate-800/80">
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $company->name }}</td>
                        <td class="px-4 py-3">
                            @if($company->uri)
                                @php($publicUrl = url($company->uri))
                                <div class="flex flex-col gap-1">
                                    <code class="inline-flex max-w-[200px] items-center justify-between gap-2 rounded bg-slate-100 px-2 py-1 text-xs text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                        {{ $company->uri }}
                                    </code>
                                    <a href="{{ $publicUrl }}" target="_blank" rel="noopener" class="text-xs text-[#F27327] hover:underline">
                                        {{ $publicUrl }}
                                    </a>
                                </div>
                            @else
                                <span class="text-xs text-slate-400">URI não configurada</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $company->locale }}</td>
                        <td class="px-4 py-3">
                            @if($company->is_active)
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200">Ativa</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-300">Suspensa</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('adminroot.companies.edit', $company) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">Editar</a>
                            <form action="{{ route('adminroot.companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Remover empresa? Esta ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 rounded-md border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50 dark:border-red-600/70 dark:text-red-300 dark:hover:bg-red-900/20">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-slate-500">Nenhuma empresa cadastrada ainda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $companies->links() }}</div>
@endsection
