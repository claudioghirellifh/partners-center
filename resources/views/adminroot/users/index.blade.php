@extends('adminroot.layouts.app')

@section('header', 'Usuários')

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-300/40 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-400/30 dark:bg-emerald-900/20 dark:text-emerald-200">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-300/40 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-400/30 dark:bg-red-900/20 dark:text-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-500">Gerencie usuários (Admins e Sellers) cadastrados.</p>
        <a href="{{ route('adminroot.users.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">Novo admin</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white/80 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
        <table class="min-w-full text-left text-sm text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-100/80 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-900/40 dark:text-slate-400">
                <tr>
                    <th class="px-4 py-3">Nome</th>
                    <th class="px-4 py-3">E-mail</th>
                    <th class="px-4 py-3">Papel</th>
                    <th class="px-4 py-3">Empresa</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-t border-slate-200/80 dark:border-slate-800/80">
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ ucfirst($user->role) }}</td>
                        <td class="px-4 py-3">{{ $user->company?->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($user->is_active)
                                <span class="rounded bg-emerald-500/15 px-2 py-1 text-xs font-medium text-emerald-600">Ativo</span>
                            @else
                                <span class="rounded bg-slate-500/15 px-2 py-1 text-xs font-medium text-slate-500">Inativo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('adminroot.users.edit', $user) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">Editar</a>
                            @php $canDelete = ! $user->isRoot() && $user->id !== auth('root')->id(); @endphp
                            <form action="{{ route('adminroot.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Excluir usuário? Esta ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md border px-3 py-1.5 text-xs font-medium transition {{ $canDelete ? 'border-red-300 text-red-700 hover:bg-red-50 dark:border-red-600/70 dark:text-red-300 dark:hover:bg-red-900/20' : 'cursor-not-allowed border-slate-200 text-slate-400 dark:border-slate-700' }}" {{ $canDelete ? '' : 'disabled' }}>
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-500">Nenhum usuário encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
@endsection
