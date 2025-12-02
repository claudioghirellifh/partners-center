<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerStoreRequest;
use App\Http\Requests\Admin\CustomerUpdateRequest;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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

        $company->customers()->create($request->validated());

        return redirect()
            ->route('admin.customers.index', ['company' => $company])
            ->with('status', 'Cliente cadastrado com sucesso.');
    }

    public function edit(Company $company, Customer $customer): View
    {
        $this->authorizeCustomer($company, $customer);

        return view('admin.customers.edit', compact('company', 'customer'));
    }

    public function update(CustomerUpdateRequest $request, Company $company, Customer $customer): RedirectResponse
    {
        $this->authorizeCustomer($company, $customer);

        $customer->update($request->validated());

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
}
