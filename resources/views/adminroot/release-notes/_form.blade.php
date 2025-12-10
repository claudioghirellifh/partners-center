@csrf
@if(isset($releaseNote) && $releaseNote->exists)
    @method('PUT')
@endif

<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Versão</label>
            <input
                type="text"
                name="version"
                value="{{ old('version', $releaseNote->version ?? '') }}"
                required
                class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white"
                placeholder="Ex.: 1.3.0"
            >
            @error('version')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Título / resumo (opcional)</label>
            <input
                type="text"
                name="title"
                value="{{ old('title', $releaseNote->title ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white"
                placeholder="Ex.: Melhorias no onboarding"
            >
            @error('title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Notas / changelog</label>
            <textarea
                name="notes"
                rows="8"
                class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white"
                placeholder="Uma linha por item. Ex.:&#10;- Ajustamos o fluxo de cobrança&#10;- Novo alerta para clientes inativos"
            >{{ old('notes', $releaseNote->notes ?? '') }}</textarea>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Sugestão: escreva um item por linha para exibir como lista para os admins.</p>
            @error('notes')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="space-y-4">
        <div class="rounded-xl border border-slate-200 bg-white/80 p-4 dark:border-slate-800 dark:bg-slate-900/50">
            <p class="text-sm font-semibold text-slate-800 dark:text-white">Exibição</p>
            <div class="mt-3 space-y-3 text-sm text-slate-700 dark:text-slate-300">
                <label class="flex items-start gap-3">
                    <input type="hidden" name="is_visible" value="0">
                    <input
                        type="checkbox"
                        name="is_visible"
                        value="1"
                        class="mt-1 h-4 w-4 rounded border-slate-300 text-[#F27327] focus:ring-[#F27327]"
                        @checked(old('is_visible', $releaseNote->is_visible ?? true))
                    >
                    <span>Mostrar no painel dos admins</span>
                </label>
                <label class="flex items-start gap-3">
                    <input type="hidden" name="is_current" value="0">
                    <input
                        type="checkbox"
                        name="is_current"
                        value="1"
                        class="mt-1 h-4 w-4 rounded border-slate-300 text-[#F27327] focus:ring-[#F27327]"
                        @checked(old('is_current', $releaseNote->is_current ?? false))
                    >
                    <div>
                        <span>Marcar como versão atual</span>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Exibe esta versão ao lado do botão de sair no painel admin.</p>
                    </div>
                </label>
            </div>
            @error('is_visible')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
            @error('is_current')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="rounded-xl border border-slate-200 bg-white/80 p-4 dark:border-slate-800 dark:bg-slate-900/50">
            <p class="text-sm font-semibold text-slate-800 dark:text-white">Avisos (opcional)</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Preencha para exibir um alerta destacado dentro do modal de versão.</p>

            <div class="mt-3">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Mensagem</label>
                <textarea
                    name="alert_message"
                    rows="3"
                    class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white"
                    placeholder="Ex.: Atenção: programamos uma janela de manutenção na sexta às 22h."
                >{{ old('alert_message', $releaseNote->alert_message ?? '') }}</textarea>
                @error('alert_message')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Gravidade</label>
                <select
                    name="alert_level"
                    class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white"
                >
                    @php($alertLevel = old('alert_level', $releaseNote->alert_level ?? ''))
                    <option value="" @selected($alertLevel === '')>Informativo</option>
                    <option value="info" @selected($alertLevel === 'info')>Info</option>
                    <option value="warning" @selected($alertLevel === 'warning')>Aviso</option>
                    <option value="critical" @selected($alertLevel === 'critical')>Crítico</option>
                </select>
                @error('alert_level')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">
        {{ $submitLabel ?? 'Salvar versão' }}
    </button>
    <a href="{{ route('adminroot.release-notes.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
        Cancelar
    </a>
</div>
