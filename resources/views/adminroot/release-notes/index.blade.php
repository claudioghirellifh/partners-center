@extends('adminroot.layouts.app')

@section('header', 'Versões & avisos')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Controle a versão exibida no painel dos admins, publique notas de mudança e envie avisos rápidos.
        </p>
        <a href="{{ route('adminroot.release-notes.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">
            Nova versão / aviso
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg border border-amber-300/60 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-500/40 dark:bg-amber-900/20 dark:text-amber-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white/80 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
        <table class="min-w-full text-left text-sm text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-100/80 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-900/40 dark:text-slate-400">
                <tr>
                    <th class="px-4 py-3">Versão</th>
                    <th class="px-4 py-3">Resumo</th>
                    <th class="px-4 py-3">Aviso</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Atualizado</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($releaseNotes as $note)
                    <tr class="border-t border-slate-200/80 dark:border-slate-800/80">
                        <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                            <div class="flex items-center gap-2">
                                <span>v{{ $note->version }}</span>
                                @if ($note->is_current)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-100 dark:ring-emerald-800/70">
                                        Atual
                                    </span>
                                @endif
                                @if (! $note->is_visible)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600 ring-1 ring-slate-200 dark:bg-slate-900/40 dark:text-slate-200 dark:ring-slate-700/70">
                                        Oculto
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ $note->title ?? '—' }}</p>
                            @if ($note->notes)
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Str::limit(strip_tags($note->notes), 80) }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($note->alert_message)
                                @php
                                    $alertStyles = [
                                        'warning' => 'bg-amber-50 text-amber-800 ring-amber-200 dark:bg-amber-900/30 dark:text-amber-100 dark:ring-amber-800/60',
                                        'critical' => 'bg-red-50 text-red-800 ring-red-200 dark:bg-red-900/30 dark:text-red-100 dark:ring-red-800/60',
                                        'info' => 'bg-blue-50 text-blue-800 ring-blue-200 dark:bg-blue-900/30 dark:text-blue-100 dark:ring-blue-800/60',
                                    ];
                                    $variant = $alertStyles[$note->alert_level ?? 'info'] ?? $alertStyles['info'];
                                @endphp
                                <div class="inline-flex rounded-lg px-3 py-1 text-xs font-semibold ring-1 {{ $variant }}">
                                    {{ ucfirst($note->alert_level ?? 'info') }}
                                </div>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Str::limit($note->alert_message, 80) }}</p>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full {{ $note->is_visible ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    <span>{{ $note->is_visible ? 'Visível' : 'Oculto' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                    <span class="h-2 w-2 rounded-full {{ $note->is_current ? 'bg-[#F27327]' : 'bg-slate-400' }}"></span>
                                    <span>{{ $note->is_current ? 'Versão atual' : 'Não marcado como atual' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                            {{ optional($note->updated_at)->format('d/m/Y H:i') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('adminroot.release-notes.edit', $note) }}" class="inline-flex items-center gap-1 rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">Editar</a>
                            <form action="{{ route('adminroot.release-notes.destroy', $note) }}" method="POST" class="inline" onsubmit="return confirm('Remover esta nota de versão?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 rounded-md border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50 dark:border-red-600/70 dark:text-red-300 dark:hover:bg-red-900/20">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">
                            Nenhuma versão cadastrada ainda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $releaseNotes->links() }}</div>
@endsection
