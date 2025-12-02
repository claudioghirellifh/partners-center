@csrf
@if(isset($project))
    @method('PUT')
@endif

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nome do projeto</label>
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
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cliente vinculado</label>
        <select name="customer_id" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
            <option value="">Selecione um cliente</option>
            @forelse($customers as $customer)
                <option value="{{ $customer->id }}" @selected(old('customer_id', $project->customer_id ?? '') == $customer->id)>{{ $customer->name }} ({{ $customer->email }})</option>
            @empty
                <option value="" disabled>Nenhum cliente cadastrado</option>
            @endforelse
        </select>
        @if($customers->isEmpty())
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Cadastre clientes antes de vinculá-los.</p>
        @endif
        @error('customer_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    @php($setupEnabled = (bool) old('charge_setup', $project->charge_setup ?? false))
    <div class="md:col-span-2">
        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
            <input type="checkbox" name="charge_setup" value="1" class="h-5 w-5 rounded border-slate-300 text-[color:var(--brand)] focus:ring-[color:var(--brand)]/40 dark:border-slate-600" data-setup-toggle data-setup-target="#setup-fee-field" {{ $setupEnabled ? 'checked' : '' }}>
            Cobrar Setup
        </label>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Selecione para adicionar uma cobrança pontual de implantação.</p>
    </div>
    <div id="setup-fee-field" class="md:col-span-2 {{ $setupEnabled ? '' : 'hidden' }}">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Valor do setup</label>
        <input type="text" name="setup_fee" id="setup-fee-input" value="{{ old('setup_fee', isset($project) && $project->setup_fee !== null ? 'R$ ' . number_format($project->setup_fee, 2, ',', '.') : '') }}" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white" data-money-input {{ $setupEnabled ? '' : 'disabled' }} placeholder="R$ 0,00">
        @error('setup_fee')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
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

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleSetupField = (checkbox) => {
                const targetSelector = checkbox.getAttribute('data-setup-target');
                if (!targetSelector) {
                    return;
                }

                const target = document.querySelector(targetSelector);
                if (!target) {
                    return;
                }

                const moneyInput = target.querySelector('[data-money-input]');
                const enabled = checkbox.checked;

                target.classList.toggle('hidden', !enabled);

                if (moneyInput) {
                    moneyInput.disabled = !enabled;
                }
            };

            document.querySelectorAll('[data-setup-toggle]').forEach((checkbox) => {
                checkbox.addEventListener('change', () => toggleSetupField(checkbox));
                toggleSetupField(checkbox);
            });

            const formatMoneyValue = (value) => {
                const digits = value.replace(/\D/g, '');
                if (!digits) {
                    return '';
                }

                const number = parseInt(digits, 10) / 100;
                return number.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL',
                });
            };

            document.querySelectorAll('[data-money-input]').forEach((input) => {
                const handleInput = () => {
                    input.value = formatMoneyValue(input.value);
                };

                input.addEventListener('input', handleInput);
                input.addEventListener('blur', handleInput);

                if (input.value) {
                    input.value = formatMoneyValue(input.value);
                }
            });
        });
    </script>
@endonce
