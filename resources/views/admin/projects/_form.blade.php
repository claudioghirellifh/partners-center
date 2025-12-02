@csrf
@if(isset($project))
    @method('PUT')
@endif

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nome do cliente/projeto</label>
        <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Plano associado</label>
        <select name="plan_id" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
            <option value="" disabled {{ old('plan_id', $project->plan_id ?? '') ? '' : 'selected' }}>Selecione um plano</option>
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}" @selected(old('plan_id', $project->plan_id ?? '') == $plan->id)>{{ $plan->name }} - R$ {{ number_format($plan->monthly_price, 2, ',', '.') }}/mês</option>
            @endforeach
        </select>
        @error('plan_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Ciclo de cobrança</label>
        <select name="billing_cycle" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
            <option value="{{ \App\Models\Project::BILLING_MONTHLY }}" @selected(old('billing_cycle', $project->billing_cycle ?? '') === \App\Models\Project::BILLING_MONTHLY)>Mensal</option>
            <option value="{{ \App\Models\Project::BILLING_ANNUAL }}" @selected(old('billing_cycle', $project->billing_cycle ?? '') === \App\Models\Project::BILLING_ANNUAL)>Anual</option>
        </select>
        @error('billing_cycle')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Data de início</label>
        <input type="date" name="starts_on" value="{{ old('starts_on', optional($project->starts_on ?? null)->format('Y-m-d')) }}" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('starts_on')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Notas internas</label>
    <textarea name="notes" rows="4" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">{{ old('notes', $project->notes ?? '') }}</textarea>
    @error('notes')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div class="flex items-center gap-3">
    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[color:var(--brand)] px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
        {{ $submitLabel ?? 'Salvar projeto' }}
    </button>
    <a href="{{ route('admin.projects.index', ['company' => $company]) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancelar</a>
</div>
