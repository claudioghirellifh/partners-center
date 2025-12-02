<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectStoreRequest;
use App\Http\Requests\Admin\ProjectUpdateRequest;
use App\Mail\IuguPaymentLinksMail;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Project;
use App\Services\Iugu\IuguClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class ProjectController extends Controller
{
    public function index(): View
    {
        /** @var Company $company */
        $company = request()->attributes->get('company');

        $projects = $company->projects()
            ->with(['plan', 'customer'])
            ->latest('id')
            ->paginate(12);

        return view('admin.projects.index', compact('company', 'projects'));
    }

    public function create(): View
    {
        $company = request()->attributes->get('company');
        $plans = Plan::query()->orderBy('name')->get();
        $customers = $company->customers()->orderBy('name')->get();

        return view('admin.projects.create', compact('company', 'plans', 'customers'));
    }

    public function store(ProjectStoreRequest $request): RedirectResponse
    {
        $company = request()->attributes->get('company');

        $data = $request->validated();
        $subscriptionMode = $data['iugu_subscription_mode'] ?? 'existing';
        unset($data['iugu_subscription_mode']);

        $plan = Plan::findOrFail($data['plan_id']);
        $customer = null;
        $paymentLinks = [];
        if (!empty($data['customer_id'])) {
            $customer = $company->customers()->find($data['customer_id']);
            if ($customer) {
                $data['client_email'] = $customer->email;
            }
        }

        if ($data['billing_origin'] === Project::ORIGIN_IUGU) {
            if ($subscriptionMode === 'create') {
                $subscriptionResult = $this->createIuguSubscription($plan, $customer);
                $data['iugu_subscription_id'] = $subscriptionResult['subscription_id'];
                if (!empty($subscriptionResult['payment_link'])) {
                    $paymentLinks[] = [
                        'label' => 'Assinatura do plano '.$plan->name,
                        'url' => $subscriptionResult['payment_link'],
                    ];
                }
            } elseif (empty($data['iugu_subscription_id'])) {
                throw ValidationException::withMessages([
                    'iugu_subscription_id' => 'Informe o ID da assinatura na Iugu ou escolha criar automaticamente.',
                ]);
            }

            if ($subscriptionMode === 'create' && !empty($data['charge_setup']) && (float) ($data['setup_fee'] ?? 0) > 0) {
                $setupInvoice = $this->createSetupInvoice($customer, (float) $data['setup_fee'], $data['name']);
                if ($setupInvoice && !empty($setupInvoice['payment_link'])) {
                    $paymentLinks[] = [
                        'label' => 'Assinatura do plano '.$plan->name,
                        'url' => $setupInvoice['payment_link'],
                        'type' => 'subscription',
                    ];
                }
            }
        } else {
            $data['iugu_subscription_id'] = null;
        }

        $project = $company->projects()->create($data);

        if (!empty($paymentLinks)) {
            $this->sendPaymentLinks($customer, $project, $paymentLinks);
        }

        return redirect()->route('admin.projects.index', ['company' => $company])
            ->with('status', 'Projeto criado com sucesso.');
    }

    public function edit(\App\Models\Company $company, Project $project): View
    {
        $this->authorizeProject($company, $project);

        $plans = Plan::query()->orderBy('name')->get();
        $customers = $company->customers()->orderBy('name')->get();

        return view('admin.projects.edit', compact('company', 'project', 'plans', 'customers'));
    }

    public function update(ProjectUpdateRequest $request, \App\Models\Company $company, Project $project): RedirectResponse
    {
        $this->authorizeProject($company, $project);

        $data = $request->validated();
        $subscriptionMode = $data['iugu_subscription_mode'] ?? 'existing';
        unset($data['iugu_subscription_mode']);

        $plan = Plan::findOrFail($data['plan_id']);
        $customer = null;
        $paymentLinks = [];
        if (!empty($data['customer_id'])) {
            $customer = $company->customers()->find($data['customer_id']);
            if ($customer) {
                $data['client_email'] = $customer->email;
            }
        }

        if ($data['billing_origin'] === Project::ORIGIN_IUGU) {
            if ($subscriptionMode === 'create' && empty($project->iugu_subscription_id)) {
                $subscriptionResult = $this->createIuguSubscription($plan, $customer);
                $data['iugu_subscription_id'] = $subscriptionResult['subscription_id'];
                if (!empty($subscriptionResult['payment_link'])) {
                    $paymentLinks[] = [
                        'label' => 'Assinatura do plano '.$plan->name,
                        'url' => $subscriptionResult['payment_link'],
                    ];
                }
                if ($subscriptionMode === 'create' && !empty($data['charge_setup']) && (float) ($data['setup_fee'] ?? 0) > 0) {
                    $setupInvoice = $this->createSetupInvoice($customer, (float) $data['setup_fee'], $data['name']);
                    if ($setupInvoice && !empty($setupInvoice['payment_link'])) {
                        $paymentLinks[] = [
                            'label' => 'Assinatura do plano '.$plan->name,
                            'url' => $setupInvoice['payment_link'],
                            'type' => 'subscription',
                        ];
                    }
                }
            } elseif ($subscriptionMode === 'create') {
                $data['iugu_subscription_id'] = $project->iugu_subscription_id;
            } elseif (empty($data['iugu_subscription_id'])) {
                throw ValidationException::withMessages([
                    'iugu_subscription_id' => 'Informe o ID da assinatura na Iugu ou escolha criar automaticamente.',
                ]);
            }
        } else {
            $data['iugu_subscription_id'] = null;
        }

        $project->update($data);

        if (!empty($paymentLinks)) {
            $this->sendPaymentLinks($customer, $project->fresh(), $paymentLinks);
        }

        return redirect()->route('admin.projects.index', ['company' => $company])
            ->with('status', 'Projeto atualizado com sucesso.');
    }

    public function destroy(\App\Models\Company $company, Project $project): RedirectResponse
    {
        $this->authorizeProject($company, $project);

        $project->delete();

        return redirect()->route('admin.projects.index', ['company' => $company])
            ->with('status', 'Projeto removido.');
    }

    protected function authorizeProject($company, Project $project): void
    {
        if ((int) $project->company_id !== (int) $company->id) {
            abort(403);
        }
    }

    protected function createIuguSubscription(Plan $plan, ?Customer $customer): array
    {
        if (! $customer) {
            throw ValidationException::withMessages([
                'customer_id' => 'Selecione um cliente para criar a assinatura na Iugu.',
            ]);
        }

        if (! $customer->iugu_customer_id) {
            throw ValidationException::withMessages([
                'customer_id' => 'O cliente selecionado precisa estar vinculado a um ID da Iugu.',
            ]);
        }

        if (! $plan->plan_id) {
            throw ValidationException::withMessages([
                'plan_id' => 'O plano selecionado não possui identificador na Iugu. Sincronize os planos antes de prosseguir.',
            ]);
        }

        $client = $this->resolveIuguClient();
        if (! $client) {
            throw ValidationException::withMessages([
                'billing_origin' => 'Configure um token da Iugu nas integrações Root para criar assinaturas automaticamente.',
            ]);
        }

        try {
            $response = $client->createSubscription([
                'plan_identifier' => $plan->plan_id,
                'customer_id' => $customer->iugu_customer_id,
            ]);
        } catch (\Throwable $exception) {
            throw ValidationException::withMessages([
                'billing_origin' => 'Falha ao criar assinatura na Iugu: '.$exception->getMessage(),
            ]);
        }

        if (empty($response['id'])) {
            throw ValidationException::withMessages([
                'billing_origin' => 'A Iugu não retornou o identificador da assinatura criada.',
            ]);
        }

        $paymentLink = $this->extractInvoiceLink($response);

        return [
            'subscription_id' => $response['id'],
            'payment_link' => $paymentLink,
        ];
    }

    protected function resolveIuguClient(): ?IuguClient
    {
        try {
            return app(IuguClient::class);
        } catch (RuntimeException $exception) {
            return null;
        }
    }

    protected function extractInvoiceLink(array $subscriptionResponse): ?string
    {
        $recent = $subscriptionResponse['recent_invoices'] ?? [];
        foreach ($recent as $invoice) {
            if (!empty($invoice['secure_url'])) {
                return $invoice['secure_url'];
            }
            if (!empty($invoice['id'])) {
                $client = $this->resolveIuguClient();
                if ($client) {
                    try {
                        $invoiceData = $client->getInvoice($invoice['id']);
                        if (!empty($invoiceData['secure_url'])) {
                            return $invoiceData['secure_url'];
                        }
                    } catch (\Throwable) {
                        // ignore
                    }
                }
            }
        }

        return null;
    }

    protected function createSetupInvoice(?Customer $customer, float $amount, string $projectName): ?array
    {
        if (! $customer) {
            throw ValidationException::withMessages([
                'customer_id' => 'Selecione um cliente para emitir a cobrança de setup.',
            ]);
        }

        if (! $customer->iugu_customer_id) {
            throw ValidationException::withMessages([
                'customer_id' => 'O cliente precisa estar vinculado à Iugu para gerar a cobrança de setup automaticamente.',
            ]);
        }

        $client = $this->resolveIuguClient();
        if (! $client) {
            throw ValidationException::withMessages([
                'billing_origin' => 'Configure o token da Iugu para gerar cobranças automaticamente.',
            ]);
        }

        $payload = [
            'customer_id' => $customer->iugu_customer_id,
            'due_date' => now()->toDateString(),
            'email' => $customer->email,
            'items' => [
                [
                    'description' => 'Setup do projeto '.$projectName,
                    'quantity' => 1,
                    'price_cents' => (int) round($amount * 100),
                ],
            ],
        ];

        try {
            $invoice = $client->createInvoice($payload);
        } catch (\Throwable $exception) {
            throw ValidationException::withMessages([
                'billing_origin' => 'Falha ao gerar cobrança de setup: '.$exception->getMessage(),
            ]);
        }

        if (empty($invoice['id'])) {
            return null;
        }

        return [
            'invoice_id' => $invoice['id'],
            'payment_link' => $invoice['secure_url'] ?? null,
        ];
    }

    protected function sendPaymentLinks(?Customer $customer, Project $project, array $links): void
    {
        $project->loadMissing('company', 'customer');
        $email = $project->client_email ?? $project->customer?->email ?? $customer?->email;
        if (! $email) {
            return;
        }

        try {
            foreach ($links as $link) {
                Mail::to($email)->send(new IuguPaymentLinksMail($project, [$link]));
            }
        } catch (\Throwable) {
            // falha no envio não deve impedir o fluxo de criação
        }
    }
}
