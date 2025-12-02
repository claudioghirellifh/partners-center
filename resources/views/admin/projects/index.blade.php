@extends('admin.layouts.app')

@section('header', 'Projetos / Clientes')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">Organize clientes/projetos vinculados ao seu plano atual.</p>
        <a href="{{ route('admin.projects.create', ['company' => $company]) }}" class="inline-flex items-center gap-2 rounded-lg bg-[color:var(--brand)] px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90">Novo projeto</a>
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
                    <th class="px-4 py-3">Plano</th>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Ciclo</th>
                    <th class="px-4 py-3">Início</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr class="border-t border-slate-200/80 dark:border-slate-800/80">
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $project->name }}</td>
                        <td class="px-4 py-3">{{ $project->plan?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $project->customer?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            {{ $project->billing_cycle === \App\Models\Project::BILLING_ANNUAL ? 'Anual' : 'Mensal' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $project->starts_on?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.projects.edit', ['company' => $company, 'project' => $project]) }}" class="inline-flex items-center gap-1 rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">Editar</a>
                            <form action="{{ route('admin.projects.destroy', ['company' => $company, 'project' => $project]) }}" method="POST" class="inline" onsubmit="return confirm('Remover projeto? Esta ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 rounded-md border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50 dark:border-red-600/70 dark:text-red-300 dark:hover:bg-red-900/20">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">Nenhum projeto cadastrado ainda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $projects->links() }}</div>
@endsection
