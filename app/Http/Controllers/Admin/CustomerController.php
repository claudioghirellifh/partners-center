<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerStoreRequest;
use App\Http\Requests\Admin\CustomerUpdateRequest;
use App\Http\Requests\Admin\CustomerInvoiceStoreRequest;
use App\Mail\IuguChargeMail;
use App\Models\Company;
use App\Models\Customer;
use App\Services\Iugu\IuguClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use RuntimeException;

class CustomerController extends Controller
{
    public function index(): View
    {
        /** @var Company $company */
        $company = request()->attributes->get('company');

        $customers = $company->customers()
            ->latest('id')
            ->paginate(15);

        return view('admin.customers.index', compact('company', 'customers'));
    }

    public function create(): View
    {
        $company = request()->attributes->get('company');

        return view('admin.customers.create', compact('company'));
    }

    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        $company = request()->attributes->get('company');

        $data = $request->validated();
        $iuguMode = $data['iugu_mode'] ?? 'create';
        unset($data['iugu_mode']);

        if ($iuguMode === 'create') {
            $iuguClient = $this->resolveIuguClient();
            if (! $iuguClient) {
                return redirect()->back()->withInput()
                    ->withErrors(['iugu' => 'Configure um token da Iugu antes de criar clientes automaticamente.']);
            }

            try {
                $response = $iuguClient->createCustomer($this->makeIuguCustomerPayload($data));
                $data['iugu_customer_id'] = $response['id'] ?? null;
            } catch (\Throwable $exception) {
                return redirect()->back()->withInput()
                    ->withErrors(['iugu' => 'Falha ao criar cliente na Iugu: '.$exception->getMessage()]);
            }
        }

        $company->customers()->create($data);

        return redirect()
            ->route('admin.customers.index', ['company' => $company])
            ->with('status', 'Cliente cadastrado com sucesso.');
    }

    public function edit(Company $company, Customer $customer): View
    {
        $this->authorizeCustomer($company, $customer);

        return view('admin.customers.edit', compact('company', 'customer'));
    }

    public function invoices(Company $company, Customer $customer): RedirectResponse|View
    {
        $this->authorizeCustomer($company, $customer);

        if (! $customer->iugu_customer_id) {
            return redirect()
                ->route('admin.customers.index', ['company' => $company])
                ->withErrors(['customer' => 'Cadastre ou vincule o ID do cliente na Iugu para consultar faturas.']);
        }

        $iuguClient = $this->resolveIuguClient();
        if (! $iuguClient) {
            return redirect()
                ->route('admin.customers.index', ['company' => $company])
                ->withErrors(['customer' => 'Token da Iugu não foi configurado.']);
        }

        try {
            $response = $iuguClient->listInvoices([
                'customer_id' => $customer->iugu_customer_id,
                'limit' => 20,
                'sortBy' => 'created_at',
                'order' => 'desc',
            ]);
            $invoices = $response['items'] ?? (is_array($response) ? $response : []);
        } catch (\Throwable $exception) {
            return redirect()
                ->route('admin.customers.index', ['company' => $company])
                ->withErrors(['customer' => 'Falha ao consultar faturas na Iugu: '.$exception->getMessage()]);
        }

        return view('admin.customers.invoices', compact('company', 'customer', 'invoices'));
    }

    public function createCharge(Company $company, Customer $customer): RedirectResponse|View
    {
        $this->authorizeCustomer($company, $customer);

        if (! $customer->iugu_customer_id) {
            return redirect()
                ->route('admin.customers.index', ['company' => $company])
                ->withErrors(['customer' => 'Vincule o ID do cliente na Iugu antes de gerar cobranças avulsas.']);
        }

        return view('admin.customers.invoices.create', compact('company', 'customer'));
    }

    public function storeCharge(CustomerInvoiceStoreRequest $request, Company $company, Customer $customer): RedirectResponse
    {
        $this->authorizeCustomer($company, $customer);

        if (! $customer->iugu_customer_id) {
            return redirect()
                ->route('admin.customers.index', ['company' => $company])
                ->withErrors(['customer' => 'Vincule o ID do cliente na Iugu antes de gerar cobranças avulsas.']);
        }

        $isTest = $request->boolean('send_test');
        if ($isTest) {
            // Resolve o usuário logado (admin da empresa ou root em impersonação)
            $user = $request->user();
            if (! $user) {
                $user = Auth::guard('web')->user() ?? Auth::guard('root')->user();
            }
            $adminEmail = $user?->email ?? null;
            if (! $adminEmail) {
                return back()->withInput()
                    ->withErrors(['customer' => 'Não foi possível identificar o e-mail do usuário logado para o envio de teste.']);
            }

            $this->sendInvoiceEmail($customer, 'https://iugu.com/pay/preview', $request->input('email_message'), $adminEmail, $request->input('description', 'Cobrança avulsa'));

            return back()->with('status', 'E-mail de teste enviado para '.$adminEmail.'.');
        }

        $client = $this->resolveIuguClient();
        if (! $client) {
            return redirect()
                ->route('admin.customers.index', ['company' => $company])
                ->withErrors(['customer' => 'Configure o token da Iugu antes de gerar cobranças avulsas.']);
        }

        $payload = [
            'customer_id' => $customer->iugu_customer_id,
            'email' => $customer->email,
            'due_date' => $request->input('due_date') ?? now()->toDateString(),
            'items' => [[
                'description' => $request->input('description'),
                'quantity' => 1,
                'price_cents' => (int) round($request->input('amount') * 100),
            ]],
        ];

        if ($request->filled('notes')) {
            $payload['notes'] = $request->input('notes');
        }

        try {
            $invoice = $client->createInvoice($payload);
        } catch (\Throwable $exception) {
            return back()->withInput()
                ->withErrors(['customer' => 'Falha ao criar fatura na Iugu: '.$exception->getMessage()]);
        }

        $message = 'Fatura avulsa criada com sucesso.';
        if (!empty($invoice['secure_url'])) {
            $message .= ' Link: '.$invoice['secure_url'];
            $this->sendInvoiceEmail($customer, $invoice['secure_url'], $request->input('email_message'));
        }

        return redirect()
            ->route('admin.customers.index', ['company' => $company])
            ->with('status', $message);
    }

    public function update(CustomerUpdateRequest $request, Company $company, Customer $customer): RedirectResponse
    {
        $this->authorizeCustomer($company, $customer);

        $data = $request->validated();
        $iuguMode = $data['iugu_mode'] ?? 'existing';
        unset($data['iugu_mode']);

        if ($iuguMode === 'create' && ! $customer->iugu_customer_id) {
            $iuguClient = $this->resolveIuguClient();
            if (! $iuguClient) {
                return redirect()->back()->withInput()
                    ->withErrors(['iugu' => 'Configure um token da Iugu antes de criar clientes automaticamente.']);
            }

            try {
                $response = $iuguClient->createCustomer($this->makeIuguCustomerPayload($data));
                $data['iugu_customer_id'] = $response['id'] ?? null;
            } catch (\Throwable $exception) {
                return redirect()->back()->withInput()
                    ->withErrors(['iugu' => 'Falha ao criar cliente na Iugu: '.$exception->getMessage()]);
            }
        }

        $customer->update($data);

        return redirect()
            ->route('admin.customers.index', ['company' => $company])
            ->with('status', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Company $company, Customer $customer): RedirectResponse
    {
        $this->authorizeCustomer($company, $customer);

        if ($customer->projects()->exists()) {
            return redirect()
                ->route('admin.customers.index', ['company' => $company])
                ->withErrors(['customer' => 'Não é possível excluir clientes vinculados a projetos ativos.']);
        }

        $customer->delete();

        return redirect()
            ->route('admin.customers.index', ['company' => $company])
            ->with('status', 'Cliente removido.');
    }

    protected function authorizeCustomer(Company $company, Customer $customer): void
    {
        if ((int) $customer->company_id !== (int) $company->id) {
            abort(403);
        }
    }

    protected function resolveIuguClient(): ?IuguClient
    {
        try {
            return app(IuguClient::class);
        } catch (RuntimeException $exception) {
            return null;
        }
    }

    protected function makeIuguCustomerPayload(array $data): array
    {
        [$phonePrefix, $phoneNumber] = $this->splitPhone($data['phone'] ?? null);

        return array_filter([
            'email' => $data['email'],
            'name' => $data['name'],
            'cpf_cnpj' => $data['cpf_cnpj'],
            'zip_code' => $data['zip_code'],
            'street' => $data['street'],
            'number' => $data['number'],
            'district' => $data['district'] ?? null,
            'city' => $data['city'],
            'state' => $data['state'],
            'complement' => $data['complement'] ?? null,
            'phone' => $phoneNumber,
            'phone_prefix' => $phonePrefix,
        ], static fn ($value) => $value !== null && $value !== '');
    }

    protected function splitPhone(?string $phone): array
    {
        if (! $phone) {
            return [null, null];
        }

        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($digits) <= 2) {
            return [$digits ?: null, null];
        }

        $prefix = substr($digits, 0, 2);
        $number = substr($digits, 2);

        return [$prefix, $number];
    }

    protected function sendInvoiceEmail(Customer $customer, string $link, ?string $message, ?string $recipient = null, ?string $title = null): void
    {
        $email = $recipient ?: $customer->email;
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new IuguChargeMail(
                $customer->setRelation('company', $customer->company),
                $link,
                $message
            ));
        } catch (\Throwable $exception) {
            \Log::error('iugu_charge_mail_failed', [
                'customer_id' => $customer->id,
                'company_id' => $customer->company_id,
                'recipient' => $email,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
