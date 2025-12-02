<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectStoreRequest;
use App\Http\Requests\Admin\ProjectUpdateRequest;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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

        $company->projects()->create($request->validated());

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

        $project->update($request->validated());

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
}
