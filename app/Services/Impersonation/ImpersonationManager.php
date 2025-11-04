<?php

namespace App\Services\Impersonation;

use App\Models\Company;
use App\Models\CompanyImpersonation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class ImpersonationManager
{
    public const SESSION_FLAG = 'impersonating.company_id';
    public const SESSION_ROOT = 'impersonating.root_user_id';
    public const SESSION_USER = 'impersonating.user_id';

    public function __construct(private readonly Session $session)
    {
    }

    public function start(User $rootUser, Company $company, ?User $adminUser = null): void
    {
        if (! $rootUser->isRoot()) {
            throw new RuntimeException('Apenas usuários root podem iniciar impersonação.');
        }

        if (! $company->is_active) {
            throw new RuntimeException('Empresa suspensa não pode ser acessada.');
        }

        if ($this->isImpersonating()) {
            $this->stop();
        }

        $guard = Auth::guard('web');

        $adminUser ??= $this->resolveTargetAdmin($company);

        $guard->logout();
        session()->regenerate();
        session()->regenerateToken();

        $guard->login($adminUser);

        $this->session->put(self::SESSION_FLAG, $company->id);
        $this->session->put(self::SESSION_ROOT, $rootUser->id);
        $this->session->put(self::SESSION_USER, $adminUser->id);

        CompanyImpersonation::create([
            'root_user_id' => $rootUser->id,
            'company_id' => $company->id,
            'impersonated_user_id' => $adminUser->id,
            'created_at' => Carbon::now(),
        ]);
    }

    public function stop(): void
    {
        if (! $this->isImpersonating()) {
            return;
        }

        Auth::guard('web')->logout();

        $this->session->forget([self::SESSION_FLAG, self::SESSION_ROOT, self::SESSION_USER]);
        session()->regenerate();
        session()->regenerateToken();
    }

    public function isImpersonating(): bool
    {
        return $this->session->has(self::SESSION_FLAG) && $this->session->has(self::SESSION_ROOT);
    }

    public function impersonatedCompanyId(): ?int
    {
        return $this->session->get(self::SESSION_FLAG);
    }

    public function impersonatedCompany(): ?Company
    {
        $id = $this->impersonatedCompanyId();

        return $id ? Company::find($id) : null;
    }

    public function rootUser(): ?User
    {
        $id = $this->session->get(self::SESSION_ROOT);

        return $id ? User::find($id) : null;
    }

    protected function resolveTargetAdmin(Company $company): User
    {
        $admin = $company->adminUsers()
            ->where('is_active', true)
            ->orderBy('id')
            ->first();

        if (! $admin) {
            throw new RuntimeException('Nenhum administrador ativo disponível para impersonação.');
        }

        return $admin;
    }
}
