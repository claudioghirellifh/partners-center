@extends('adminroot.layouts.app')

@section('header', 'Editar empresa')

@section('content')
    <form action="{{ route('adminroot.companies.update', $company) }}" method="POST" enctype="multipart/form-data" class="grid gap-6 md:grid-cols-2">
        @csrf
        @method('PUT')

        <section class="rounded-xl border border-slate-200 bg-white/80 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 md:col-span-2">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Dados da empresa</h2>
            <div class="mt-4 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nome da empresa</label>
                    <input type="text" name="name" value="{{ old('name', $company->name) }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">URI da empresa</label>
                    <input type="text" name="uri" value="{{ old('uri', $company->uri) }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                    @error('uri')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    @php($__preview = url($company->uri))
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                        <span>Pré-visualização:</span>
                        <a href="{{ $__preview }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-1 text-[#F27327] ring-1 ring-slate-200 hover:underline dark:bg-slate-900/40 dark:text-[#F27327] dark:ring-slate-700">
                            {{ $__preview }}
                        </a>
                        <a href="{{ $__preview }}/admin/login" target="_blank" rel="noopener" class="inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-1 text-[#F27327] ring-1 ring-slate-200 hover:underline dark:bg-slate-900/40 dark:text-[#F27327] dark:ring-slate-700">
                            {{ $__preview }}/admin/login
                        </a>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status da empresa</label>
                    <select name="is_active" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                        <option value="1" @selected(old('is_active', $company->is_active ? '1' : '0')==='1')>Ativa</option>
                        <option value="0" @selected(old('is_active', $company->is_active ? '1' : '0')==='0')>Suspensa</option>
                    </select>
                    @error('is_active')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Idioma</label>
                    <select name="locale" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
                        <option value="pt-BR" @selected(old('locale', $company->locale)==='pt-BR')>Português (Brasil)</option>
                        <option value="en" @selected(old('locale', $company->locale)==='en')>English</option>
                        <option value="es-AR" @selected(old('locale', $company->locale)==='es-AR')>Español (Argentina)</option>
                    </select>
                    @error('locale')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Cor da marca</label>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="color" value="{{ old('brand_color', $company->brand_color ?? '#F27327') }}" class="h-10 w-14 cursor-pointer rounded border border-slate-300 bg-white dark:border-slate-700 dark:bg-slate-900" oninput="this.nextElementSibling.value=this.value" />
                        <input type="text" name="brand_color" value="{{ old('brand_color', $company->brand_color ?? '#F27327') }}" placeholder="#F27327" class="w-32 rounded-lg border border-slate-300 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white" oninput="this.previousElementSibling.value=this.value" />
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Hex com 6 dígitos. Ex.: #F27327</p>
                    @error('brand_color')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Logo (até 1MB)</label>
                    <input type="file" name="logo" class="mt-2 block w-full text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-[#F27327]/10 file:px-4 file:py-2 file:text-[#F27327] file:hover:bg-[#F27327]/20" />
                    @if($company->logo_path)
                        <div class="mt-3 inline-flex items-center gap-3 rounded-lg border border-slate-200 bg-white/70 p-2 dark:border-slate-700 dark:bg-slate-900/40">
                            <img src="{{ Storage::disk('public')->url($company->logo_path) }}" alt="Logo atual" class="h-16 w-16 rounded object-contain ring-1 ring-slate-200 dark:ring-slate-700" loading="lazy">
                            <span class="text-xs text-slate-500">Prévia do logo atual</span>
                        </div>
                    @endif
                    @error('logo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Favicon (.ico, .png, .svg)</label>
                    <input type="file" name="favicon" class="mt-2 block w-full text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-[#F27327]/10 file:px-4 file:py-2 file:text-[#F27327] file:hover:bg-[#F27327]/20" />
                    @if($company->favicon_path)
                        <div class="mt-3 inline-flex items-center gap-3 rounded-lg border border-slate-200 bg-white/70 p-2 dark:border-slate-700 dark:bg-slate-900/40">
                            <img src="{{ Storage::disk('public')->url($company->favicon_path) }}" alt="Favicon atual" class="h-8 w-8 rounded object-contain ring-1 ring-slate-200 dark:ring-slate-700" loading="lazy">
                            <span class="text-xs text-slate-500">Prévia do favicon atual</span>
                        </div>
                    @endif
                    @error('favicon')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <div class="md:col-span-2">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">Salvar alterações</button>
            <a href="{{ route('adminroot.companies.index') }}" class="ml-3 inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancelar</a>
        </div>
    </form>

    <form action="{{ route('adminroot.companies.destroy', $company) }}" method="POST" class="mt-4" onsubmit="return confirm('Remover empresa? Esta ação apagará também os usuários associados.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100 dark:border-red-600/70 dark:bg-red-900/20 dark:text-red-300 dark:hover:bg-red-900/30">
            Excluir empresa
        </button>
    </form>
@endsection
