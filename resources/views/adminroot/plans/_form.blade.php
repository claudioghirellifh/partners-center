@csrf
@if(isset($plan))
    @method('PUT')
@endif

<div class="grid gap-5 md:grid-cols-2">
    @if(isset($plan) && $plan->plan_id)
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Plan ID (Iugu)</label>
            <input type="text" value="{{ $plan->plan_id }}" readonly class="mt-2 w-full cursor-not-allowed rounded-lg border border-dashed border-slate-300 bg-slate-100 px-4 py-2.5 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-300">
        </div>
    @endif
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nome do plano</label>
        <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Preço mensal (R$)</label>
        <input type="number" step="0.01" min="0" name="monthly_price" value="{{ old('monthly_price', $plan->monthly_price ?? '') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('monthly_price')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Preço anual (R$)</label>
        <input type="number" step="0.01" min="0" name="annual_price" value="{{ old('annual_price', $plan->annual_price ?? '') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('annual_price')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Descrição (opcional)</label>
    <textarea name="description" rows="4" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[#F27327] focus:ring-2 focus:ring-[#F27327]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">{{ old('description', $plan->description ?? '') }}</textarea>
    @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div class="flex items-center gap-3">
    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#F27327] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#F27327]/90">
        {{ $submitLabel ?? 'Salvar plano' }}
    </button>
    <a href="{{ route('adminroot.plans.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancelar</a>
</div>
