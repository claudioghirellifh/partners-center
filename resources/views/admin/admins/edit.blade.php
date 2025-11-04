@extends('admin.layouts.app')

@section('header', 'Editar administrador')

@section('content')
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Atualize os dados do administrador. Alterações de e-mail disparam um novo envio de credenciais.
    </p>

    <form action="{{ route('admin.admins.update', ['company' => $company, 'adminUser' => $adminUser]) }}" method="POST" class="mt-6 grid gap-6 md:max-w-3xl">
        @csrf
        @method('PUT')

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nome completo</label>
                <input type="text" name="name" value="{{ old('name', $adminUser->name) }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">E-mail corporativo</label>
                <input type="email" name="email" value="{{ old('email', $adminUser->email) }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="md:w-1/3">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Status</label>
            <select name="is_active" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white" {{ $isSelf ? 'disabled' : '' }}>
                <option value="1" @selected(old('is_active', $adminUser->is_active ? '1' : '0')==='1')>Ativo</option>
                <option value="0" @selected(old('is_active', $adminUser->is_active ? '1' : '0')==='0')>Suspenso</option>
            </select>
            @if($isSelf)
                <input type="hidden" name="is_active" value="1">
                <p class="mt-1 text-xs text-amber-500 dark:text-amber-300">
                    Você não pode suspender o seu próprio usuário.
                </p>
            @endif
            @error('is_active')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[color:var(--brand)] px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                Salvar alterações
            </button>
            <a href="{{ route('admin.admins.index', ['company' => $company]) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                Voltar
            </a>
        </div>
    </form>
@endsection
