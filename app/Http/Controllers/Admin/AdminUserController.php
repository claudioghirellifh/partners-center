<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserStoreRequest;
use App\Http\Requests\Admin\AdminUserUpdateRequest;
use App\Mail\AdminWelcomeMail;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        /** @var Company $company */
        $company = request()->attributes->get('company');

        $admins = User::query()
            ->where('company_id', $company->id)
            ->where('role', User::ROLE_ADMIN)
            ->orderBy('name')
            ->get();

        return view('admin.admins.index', compact('company', 'admins'));
    }

    public function create(): View
    {
        /** @var Company $company */
        $company = request()->attributes->get('company');

        return view('admin.admins.create', compact('company'));
    }

    public function store(AdminUserStoreRequest $request): RedirectResponse
    {
        /** @var Company $company */
        $company = $request->attributes->get('company');

        $data = $request->validated();

        $tempPassword = Str::random(12);

        $user = new User();
        $user->company_id = $company->id;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = User::ROLE_ADMIN;
        $user->is_active = (bool) ($data['is_active'] ?? true);
        $user->password = Hash::make($tempPassword);
        $user->save();

        $loginUrl = route('admin.login.form', ['company' => $company]);
        Mail::to($user->email)->send(new AdminWelcomeMail($company, $user, $tempPassword, $loginUrl));

        return redirect()
            ->route('admin.admins.index', ['company' => $company])
            ->with('status', 'Administrador criado com sucesso. Enviamos as credenciais por e-mail.');
    }

    public function edit(Company $company, User $adminUser): View
    {
        $this->guardAgainstForeignUser($company, $adminUser);

        return view('admin.admins.edit', [
            'company' => $company,
            'adminUser' => $adminUser,
            'isSelf' => Auth::guard('web')->id() === $adminUser->id,
        ]);
    }

    public function update(AdminUserUpdateRequest $request, Company $company, User $adminUser): RedirectResponse
    {
        $this->guardAgainstForeignUser($company, $adminUser);

        $data = $request->validated();

        $isSelf = Auth::guard('web')->id() === $adminUser->id;
        if ($isSelf && (bool) $data['is_active'] === false) {
            return back()
                ->withErrors(['is_active' => 'Você não pode suspender o seu próprio acesso.'])
                ->withInput();
        }

        $emailChanged = strcasecmp($adminUser->email, $data['email']) !== 0;

        $adminUser->name = $data['name'];
        $adminUser->email = $data['email'];
        $adminUser->is_active = (bool) $data['is_active'];

        $tempPassword = null;
        if ($emailChanged) {
            $tempPassword = Str::random(12);
            $adminUser->password = Hash::make($tempPassword);
        }

        $adminUser->save();

        if ($emailChanged) {
            $loginUrl = route('admin.login.form', ['company' => $company]);
            Mail::to($adminUser->email)->send(new AdminWelcomeMail($company, $adminUser, $tempPassword, $loginUrl));
        }

        return redirect()
            ->route('admin.admins.index', ['company' => $company])
            ->with('status', 'Administrador atualizado com sucesso.'.($emailChanged ? ' Enviamos novas credenciais para o e-mail informado.' : ''));
    }

    protected function guardAgainstForeignUser(Company $company, User $user): void
    {
        if ((int) $user->company_id !== (int) $company->id || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }
    }
}
