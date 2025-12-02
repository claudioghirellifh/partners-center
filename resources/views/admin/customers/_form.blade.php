@csrf
@if(isset($customer))
    @method('PUT')
@endif

@php($iuguMode = old('iugu_mode', ($customer->iugu_customer_id ?? null) ? 'existing' : 'create'))

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nome completo</label>
        <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Email</label>
        <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Telefone</label>
        <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" data-mask="phone" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">CPF/CNPJ</label>
        <input type="text" name="cpf_cnpj" value="{{ old('cpf_cnpj', $customer->cpf_cnpj ?? '') }}" required data-mask="cpf-cnpj" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('cpf_cnpj')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2 rounded-xl border border-slate-200 bg-white/70 p-4 dark:border-slate-700 dark:bg-slate-900/40">
        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Integração com Iugu</p>
        <div class="mt-3 flex flex-col gap-2 text-sm text-slate-600 dark:text-slate-300">
            <label class="inline-flex items-center gap-2">
                <input type="radio" name="iugu_mode" value="create" class="h-4 w-4 text-[color:var(--brand)] focus:ring-[color:var(--brand)]/40" data-iugu-mode @checked($iuguMode === 'create')>
                Criar automaticamente o cliente na Iugu
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="radio" name="iugu_mode" value="existing" class="h-4 w-4 text-[color:var(--brand)] focus:ring-[color:var(--brand)]/40" data-iugu-mode @checked($iuguMode === 'existing')>
                Já cadastrei este cliente na Iugu e quero vincular
            </label>
        </div>
        <div id="iugu-customer-id-field" class="mt-3 {{ $iuguMode === 'existing' ? '' : 'hidden' }}">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">ID do cliente na Iugu</label>
            <input type="text" name="iugu_customer_id" value="{{ old('iugu_customer_id', $customer->iugu_customer_id ?? '') }}" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white" placeholder="ex.: 123456789">
            @error('iugu_customer_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        @error('iugu')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">CEP</label>
        <input type="text" name="zip_code" value="{{ old('zip_code', $customer->zip_code ?? '') }}" required data-mask="zip" data-cep-input class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('zip_code')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Rua</label>
        <input type="text" name="street" value="{{ old('street', $customer->street ?? '') }}" required data-cep-field="logradouro" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('street')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Número</label>
        <input type="text" name="number" value="{{ old('number', $customer->number ?? '') }}" required data-mask="house-number" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('number')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Bairro</label>
        <input type="text" name="district" value="{{ old('district', $customer->district ?? '') }}" data-cep-field="bairro" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('district')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Cidade</label>
        <input type="text" name="city" value="{{ old('city', $customer->city ?? '') }}" required data-cep-field="localidade" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('city')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Estado (UF)</label>
        <input type="text" name="state" value="{{ old('state', $customer->state ?? '') }}" maxlength="2" required data-mask="state" data-cep-field="uf" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm uppercase text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('state')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Complemento</label>
        <input type="text" name="complement" value="{{ old('complement', $customer->complement ?? '') }}" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">
        @error('complement')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-5">
    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Anotações internas</label>
    <textarea name="notes" rows="4" class="mt-2 w-full rounded-lg border border-slate-300 bg-white/80 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-[color:var(--brand)] focus:ring-2 focus:ring-[color:var(--brand)]/30 dark:border-slate-700 dark:bg-slate-950/70 dark:text-white">{{ old('notes', $customer->notes ?? '') }}</textarea>
    @error('notes')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[color:var(--brand)] px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
        {{ $submitLabel ?? 'Salvar cliente' }}
    </button>
    <a href="{{ route('admin.customers.index', ['company' => $company]) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancelar</a>
</div>

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formatters = {
                phone(value) {
                    const digits = value.replace(/\D+/g, '').slice(0, 11);
                    if (digits.length <= 2) return digits;
                    const ddd = digits.slice(0, 2);
                    const rest = digits.slice(2);
                    if (rest.length <= 4) {
                        return `(${ddd}) ${rest}`;
                    }
                    if (rest.length <= 8) {
                        return `(${ddd}) ${rest.slice(0, 4)}-${rest.slice(4)}`;
                    }
                    return `(${ddd}) ${rest.slice(0, 5)}-${rest.slice(5, 9)}`;
                },
                'cpf-cnpj'(value) {
                    const digits = value.replace(/\D+/g, '').slice(0, 14);
                    if (digits.length <= 11) {
                        let formatted = digits;
                        if (digits.length > 3) {
                            formatted = `${digits.slice(0, 3)}.${digits.slice(3)}`;
                        }
                        if (digits.length > 6) {
                            formatted = `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6)}`;
                        }
                        if (digits.length > 9) {
                            formatted = `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6, 9)}-${digits.slice(9)}`;
                        }
                        return formatted;
                    }
                    let cnpj = digits;
                    if (digits.length > 2) {
                        cnpj = `${digits.slice(0, 2)}.${digits.slice(2)}`;
                    }
                    if (digits.length > 5) {
                        cnpj = `${digits.slice(0, 2)}.${digits.slice(2, 5)}.${digits.slice(5)}`;
                    }
                    if (digits.length > 8) {
                        cnpj = `${digits.slice(0, 2)}.${digits.slice(2, 5)}.${digits.slice(5, 8)}/${digits.slice(8)}`;
                    }
                    if (digits.length > 12) {
                        cnpj = `${digits.slice(0, 2)}.${digits.slice(2, 5)}.${digits.slice(5, 8)}/${digits.slice(8, 12)}-${digits.slice(12)}`;
                    }
                    return cnpj;
                },
                zip(value) {
                    const digits = value.replace(/\D+/g, '').slice(0, 8);
                    if (digits.length <= 5) return digits;
                    return `${digits.slice(0, 5)}-${digits.slice(5)}`;
                },
                'house-number'(value) {
                    return value.replace(/\D+/g, '').slice(0, 10);
                },
                state(value) {
                    return value.replace(/[^a-zA-Z]/g, '').slice(0, 2).toUpperCase();
                },
            };

            document.querySelectorAll('[data-mask]').forEach((input) => {
                const type = input.dataset.mask;
                const formatter = formatters[type];
                if (!formatter) return;

                const applyMask = () => {
                    input.value = formatter(input.value);
                };

                input.addEventListener('input', applyMask);
                input.addEventListener('blur', applyMask);
                applyMask();
            });

            const cepInput = document.querySelector('[data-cep-input]');
            if (cepInput) {
                const fieldsMap = {};
                document.querySelectorAll('[data-cep-field]').forEach((input) => {
                    fieldsMap[input.dataset.cepField] = input;
                });

                let currentRequest = null;

                const fillAddress = (data) => {
                    ['logradouro', 'bairro', 'localidade', 'uf'].forEach((key) => {
                        const field = fieldsMap[key];
                        if (!field || !data[key]) {
                            return;
                        }

                        if (!field.value) {
                            field.value = data[key];
                            const maskType = field.dataset.mask;
                            if (maskType && formatters[maskType]) {
                                field.value = formatters[maskType](field.value);
                            }
                        }
                    });
                };

                const fetchCep = async () => {
                    const zipDigits = cepInput.value.replace(/\D+/g, '');
                    if (zipDigits.length !== 8) {
                        return;
                    }

                    if (currentRequest === zipDigits) {
                        return;
                    }
                    currentRequest = zipDigits;

                    try {
                        const response = await fetch(`https://viacep.com.br/ws/${zipDigits}/json/`);
                        const data = await response.json();
                        if (data && !data.erro) {
                            fillAddress(data);
                        }
                    } catch (e) {
                        console.error('Erro ao consultar CEP:', e);
                    }
                };

                cepInput.addEventListener('blur', fetchCep);
            }

            const toggleIuguField = () => {
                const selected = document.querySelector('input[data-iugu-mode]:checked');
                const field = document.getElementById('iugu-customer-id-field');
                if (!selected || !field) {
                    return;
                }

                const show = selected.value === 'existing';
                field.classList.toggle('hidden', !show);
            };

            document.querySelectorAll('input[data-iugu-mode]').forEach((input) => {
                input.addEventListener('change', toggleIuguField);
            });
            toggleIuguField();
        });
    </script>
@endonce
