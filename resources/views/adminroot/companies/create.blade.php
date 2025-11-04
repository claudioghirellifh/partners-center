@extends('adminroot.layouts.app')

@section('header', 'Nova empresa')

@section('content')
    <form action="{{ route('adminroot.companies.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-6 md:grid-cols-2">
        @csrf

        <section class="rounded-xl border border-slate-200 bg-white/80 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 md:col-span-2">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Dados da empresa</h2>
            <div class="mt-4 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nome da empresa</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">URI da empresa</label>
                    <input type="text" name="uri" placeholder="Ex.: catus" value="{{ old('uri') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    <p class="mt-1 text-xs text-slate-500">A URL final ficará <code>{{ config('app.url') }}/sua-uri</code>.</p>
                    @error('uri')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Idioma</label>
                    <select name="locale" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                        <option value="pt-BR" @selected(old('locale')==='pt-BR')>Português (Brasil)</option>
                        <option value="en" @selected(old('locale')==='en')>English</option>
                        <option value="es-AR" @selected(old('locale')==='es-AR')>Español (Argentina)</option>
                    </select>
                    @error('locale')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Cor da marca</label>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="color" value="{{ old('brand_color', '#F27327') }}" class="h-10 w-14 cursor-pointer rounded border border-slate-300 bg-white dark:border-slate-700 dark:bg-slate-900" oninput="this.nextElementSibling.value=this.value" />
                        <input type="text" name="brand_color" value="{{ old('brand_color', '#F27327') }}" placeholder="#F27327" class="w-32 rounded-lg border border-slate-300 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white" oninput="this.previousElementSibling.value=this.value" />
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Hex com 6 dígitos. Ex.: #F27327</p>
                    @error('brand_color')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Logo (obrigatório, até 1MB)</label>
                    <input type="file" name="logo" required class="mt-2 block w-full text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-[#F27327]/10 file:px-4 file:py-2 file:text-[#F27327] file:hover:bg-[#F27327]/20" />
                    @error('logo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Favicon (.ico, .png, .svg)</label>
                    <input type="file" name="favicon" class="mt-2 block w-full text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-[#F27327]/10 file:px-4 file:py-2 file:text-[#F27327] file:hover:bg-[#F27327]/20" />
                    @error('favicon')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white/80 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 md:col-span-2">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Admin da empresa</h2>
            <p class="mt-1 text-sm text-slate-500">O admin receberá por e-mail as instruções de acesso.</p>
            <div class="mt-4 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nome do admin</label>
                    <input type="text" name="admin_name" value="{{ old('admin_name') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    @error('admin_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-mail do admin</label>
                    <input type="email" name="admin_email" value="{{ old('admin_email') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    @error('admin_email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <div class="md:col-span-2">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">Salvar empresa</button>
            <a href="{{ route('adminroot.companies.index') }}" class="ml-3 inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancelar</a>
        </div>
    </form>
@endsection
