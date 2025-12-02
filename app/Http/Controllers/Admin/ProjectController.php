<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectStoreRequest;
use App\Http\Requests\Admin\ProjectUpdateRequest;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Project;
use App\Services\Iugu\IuguClient;
use Illuminate\Http\RedirectResponse;
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
        if (!empty($data['customer_id'])) {
            $customer = $company->customers()->find($data['customer_id']);
            if ($customer) {
                $data['client_email'] = $customer->email;
            }
        }

        if ($data['billing_origin'] === Project::ORIGIN_IUGU) {
            if ($subscriptionMode === 'create') {
                $data['iugu_subscription_id'] = $this->createIuguSubscription($plan, $customer);
            } elseif (empty($data['iugu_subscription_id'])) {
                throw ValidationException::withMessages([
                    'iugu_subscription_id' => 'Informe o ID da assinatura na Iugu ou escolha criar automaticamente.',
                ]);
            }
        } else {
            $data['iugu_subscription_id'] = null;
        }

        $company->projects()->create($data);

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
        if (!empty($data['customer_id'])) {
            $customer = $company->customers()->find($data['customer_id']);
            if ($customer) {
                $data['client_email'] = $customer->email;
            }
        }

        if ($data['billing_origin'] === Project::ORIGIN_IUGU) {
            if ($subscriptionMode === 'create' && empty($project->iugu_subscription_id)) {
                $data['iugu_subscription_id'] = $this->createIuguSubscription($plan, $customer);
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

    protected function createIuguSubscription(Plan $plan, ?Customer $customer): string
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

        return $response['id'];
    }

    protected function resolveIuguClient(): ?IuguClient
    {
        try {
            return app(IuguClient::class);
        } catch (RuntimeException $exception) {
            return null;
        }
    }
}
