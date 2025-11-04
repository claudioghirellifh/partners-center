<?php

namespace App\Http\Controllers\AdminRoot;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRoot\CompanyStoreRequest;
use App\Http\Requests\AdminRoot\CompanyUpdateRequest;
use App\Models\Company;
use App\Models\User;
use App\Mail\AdminWelcomeMail;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $companies = Company::query()
            ->latest('id')
            ->paginate(12);

        return view('adminroot.companies.index', compact('companies'));
    }

    public function create(): View
    {
        return view('adminroot.companies.create');
    }

    public function store(CompanyStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $company = new Company();
        $company->name = $data['name'];
        $company->uri = Str::of($data['uri'])->lower()->slug('-');
        $company->locale = $data['locale'];
        $company->is_active = (bool) ($data['is_active'] ?? true);
        $company->brand_color = self::normalizeColor($data['brand_color'] ?? null);

        if ($request->hasFile('logo')) {
            $company->logo_path = $request->file('logo')->store('companies/logos', 'public');
        }
        if ($request->hasFile('favicon')) {
            $company->favicon_path = $request->file('favicon')->store('companies/favicons', 'public');
        }

        $company->save();

        // Criar Admin inicial da empresa
        $admin = new User();
        $admin->name = $data['admin_name'];
        $admin->email = $data['admin_email'];
        $admin->role = User::ROLE_ADMIN;
        $admin->is_active = true;
        $admin->company_id = $company->id;
        // Senha temporária aleatória; envio de e-mail será implementado depois
        $tempPassword = Str::random(12);
        $admin->password = Hash::make($tempPassword);
        $admin->save();

        // Enviar e-mail de instruções
        $loginUrl = route('admin.login.form', ['company' => $company]);
        Mail::to($admin->email)->send(new AdminWelcomeMail($company, $admin, $tempPassword, $loginUrl));

        return redirect()
            ->route('adminroot.companies.index')
            ->with('status', 'Empresa criada com sucesso. O admin receberá as instruções de acesso por e-mail.');
    }

    public function edit(Company $company): View
    {
        return view('adminroot.companies.edit', compact('company'));
    }

    public function update(CompanyUpdateRequest $request, Company $company): RedirectResponse
    {
        $data = $request->validated();

        $company->name = $data['name'];
        $company->uri = Str::of($data['uri'])->lower()->slug('-');
        $company->locale = $data['locale'];
        $company->is_active = (bool) $data['is_active'];
        $company->brand_color = self::normalizeColor($data['brand_color'] ?? $company->brand_color);

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $company->logo_path = $request->file('logo')->store('companies/logos', 'public');
        }
        if ($request->hasFile('favicon')) {
            if ($company->favicon_path) {
                Storage::disk('public')->delete($company->favicon_path);
            }
            $company->favicon_path = $request->file('favicon')->store('companies/favicons', 'public');
        }

        $company->save();

        return redirect()
            ->route('adminroot.companies.index')
            ->with('status', 'Empresa atualizada com sucesso.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        if ($company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
        }
        if ($company->favicon_path) {
            Storage::disk('public')->delete($company->favicon_path);
        }

        $company->delete();

        return redirect()
            ->route('adminroot.companies.index')
            ->with('status', 'Empresa removida.');
    }

    protected static function normalizeColor(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $v = ltrim($value, '#');
        if (preg_match('/^[0-9A-Fa-f]{6}$/', $v) !== 1) {
            return null;
        }
        return '#'.strtoupper($v);
    }
}
