@extends('adminroot.layouts.app')

@section('header', 'Editar usuário')

@section('content')
    @php $isSelfRoot = $user->isRoot() && auth('root')->id() === $user->id; @endphp
    <form action="{{ route('adminroot.users.update', $user) }}" method="POST" class="grid gap-6 md:grid-cols-2">
        @csrf
        @method('PUT')

        <section class="rounded-xl border border-slate-200 bg-white/80 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 md:col-span-2">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Dados do usuário</h2>
            <div class="mt-4 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nome</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                    @if($isSelfRoot)
                        <input type="hidden" name="is_active" value="1">
                    @endif
                    <select name="is_active" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white" {{ $isSelfRoot ? 'disabled' : '' }}>
                        <option value="1" @selected(old('is_active', $user->is_active ? 1 : 0)==1)>Ativo</option>
                        <option value="0" @selected(old('is_active', $user->is_active ? 1 : 0)==0)>Inativo</option>
                    </select>
                    @if($isSelfRoot)
                        <p class="mt-1 text-xs text-slate-500">O usuário Root não pode suspender a si mesmo.</p>
                    @endif
                    @error('is_active')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Empresa</label>
                    <input type="text" value="{{ $user->company?->name ?? '—' }}" class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-100/70 px-4 py-2.5 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-900/40 dark:text-slate-300" disabled>
                </div>
            </div>
            <p class="mt-4 text-sm text-slate-500">Ao alterar o e-mail, o usuário receberá um novo e-mail com instruções de acesso e uma senha temporária.</p>
        </section>

        <div class="md:col-span-2 flex flex-wrap items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">Salvar alterações</button>
            <a href="{{ route('adminroot.users.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancelar</a>
        </div>
    </form>

    @php $canDelete = ! $user->isRoot() && auth('root')->id() !== $user->id; @endphp
    <form action="{{ route('adminroot.users.destroy', $user) }}" method="POST" class="mt-4" onsubmit="return confirm('Excluir usuário? Esta ação não pode ser desfeita.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center gap-2 rounded-lg border px-5 py-2.5 text-sm font-semibold transition {{ $canDelete ? 'border-red-300 text-red-700 hover:bg-red-50 dark:border-red-600/70 dark:text-red-300 dark:hover:bg-red-900/20' : 'cursor-not-allowed border-slate-200 text-slate-400 dark:border-slate-700' }}" {{ $canDelete ? '' : 'disabled' }}>
            Excluir usuário
        </button>
    </form>
@endsection
