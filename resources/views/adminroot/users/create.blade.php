@extends('adminroot.layouts.app')

@section('header', 'Novo administrador')

@section('content')
    <form action="{{ route('adminroot.users.store') }}" method="POST" class="grid gap-6 md:grid-cols-2">
        @csrf

        <section class="rounded-xl border border-slate-200 bg-white/80 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 md:col-span-2">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Dados do admin</h2>
            <div class="mt-4 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Empresa</label>
                    <select name="company_id" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white" required>
                        <option value="">Selecione...</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected(old('company_id')==$company->id)>{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nome</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <p class="mt-4 text-sm text-slate-500">O novo admin receberá um e-mail com instruções de acesso, contendo o link do painel, o e-mail e uma senha temporária.</p>
        </section>

        <div class="md:col-span-2">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">Criar admin</button>
            <a href="{{ route('adminroot.users.index') }}" class="ml-3 inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancelar</a>
        </div>
    </form>
@endsection

