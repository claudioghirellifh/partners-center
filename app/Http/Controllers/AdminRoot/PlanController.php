<?php

namespace App\Http\Controllers\AdminRoot;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRoot\PlanStoreRequest;
use App\Http\Requests\AdminRoot\PlanUpdateRequest;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
        Plan::create($request->validated());

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

        return redirect()->route('adminroot.plans.index')
            ->with('status', 'Plano atualizado com sucesso.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('adminroot.plans.index')
            ->with('status', 'Plano removido.');
    }
}
