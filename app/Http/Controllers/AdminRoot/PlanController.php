<?php

namespace App\Http\Controllers\AdminRoot;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRoot\PlanStoreRequest;
use App\Http\Requests\AdminRoot\PlanUpdateRequest;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\Iugu\IuguClient;
use Illuminate\Support\Str;
use RuntimeException;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::query()->orderBy('name')->paginate(15);

        return view('adminroot.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('adminroot.plans.create');
    }

    public function store(PlanStoreRequest $request): RedirectResponse
    {
        $plan = Plan::create($request->validated());

        try {
            $this->syncPlanToIugu($plan);
        } catch (RuntimeException $exception) {
            return redirect()->route('adminroot.plans.index')
                ->withErrors(['plan' => 'Plano salvo localmente, mas falhou ao sincronizar com a Iugu: '.$exception->getMessage()]);
        }

        return redirect()->route('adminroot.plans.index')
            ->with('status', 'Plano criado com sucesso.');
    }

    public function edit(Plan $plan): View
    {
        return view('adminroot.plans.edit', compact('plan'));
    }

    public function update(PlanUpdateRequest $request, Plan $plan): RedirectResponse
    {
        $plan->update($request->validated());

        try {
            $this->syncPlanToIugu($plan);
        } catch (RuntimeException $exception) {
            return redirect()->route('adminroot.plans.index')
                ->withErrors(['plan' => 'Plano atualizado localmente, mas falhou ao sincronizar com a Iugu: '.$exception->getMessage()]);
        }

        return redirect()->route('adminroot.plans.index')
            ->with('status', 'Plano atualizado com sucesso.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        if ($plan->projects()->exists()) {
            return redirect()->route('adminroot.plans.index')
                ->withErrors(['plan' => 'Este plano está associado a um ou mais projetos e não pode ser removido.']);
        }

        $plan->delete();

        return redirect()->route('adminroot.plans.index')
            ->with('status', 'Plano removido localmente. Lembre-se de excluir o equivalente na Iugu.');
    }

    public function syncFromIugu(): RedirectResponse
    {
        try {
            $plans = $this->resolveIugu()->listPlans();
        } catch (RuntimeException $exception) {
            return redirect()->route('adminroot.plans.index')
                ->withErrors(['plan' => 'Falha ao sincronizar planos da Iugu: '.$exception->getMessage()]);
        }

        $items = $plans['items'] ?? $plans;

        foreach ($items as $remotePlan) {
            if (! is_array($remotePlan) || empty($remotePlan['identifier'])) {
                continue;
            }

            $monthlyPrice = $this->extractMonthlyPrice($remotePlan);

            Plan::updateOrCreate(
                ['plan_id' => $remotePlan['identifier']],
                [
                    'name' => $remotePlan['name'] ?? 'Plano sem nome',
                    'monthly_price' => $monthlyPrice,
                    'description' => $this->buildDescriptionFromRemote($remotePlan),
                ]
            );
        }

        return redirect()->route('adminroot.plans.index')
            ->with('status', 'Planos sincronizados com a Iugu.');
    }

    protected function syncPlanToIugu(Plan $plan): void
    {
        $payload = [
            'name' => $plan->name,
            'identifier' => $plan->plan_id ?: Str::slug($plan->name.'-'.$plan->id.'-'.uniqid()),
            'interval' => 1,
            'interval_type' => 'months',
            'price_cents' => (int) round($plan->monthly_price * 100),
            'prices' => [
                [
                    'currency' => 'BRL',
                    'value_cents' => (int) round($plan->monthly_price * 100),
                ],
            ],
            'description' => $plan->description,
            'payable_with' => ['credit_card', 'bank_slip'],
        ];

        if ($plan->plan_id) {
            $response = $this->resolveIugu()->updatePlan($plan->plan_id, $payload);
        } else {
            $response = $this->resolveIugu()->createPlan($payload);
        }

        $plan->update(['plan_id' => $response['identifier'] ?? $payload['identifier']]);
    }

    protected function resolveIugu(): IuguClient
    {
        return app(IuguClient::class);
    }

    protected function buildDescriptionFromRemote(array $remotePlan): ?string
    {
        $data = $remotePlan;
        unset($data['name']);

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    protected function extractMonthlyPrice(array $remotePlan): float
    {
        $prices = $remotePlan['prices'] ?? [];
        $first = $prices[0]['value_cents'] ?? null;

        if ($first === null) {
            return 0.0;
        }

        return ((int) $first) / 100;
    }

}
