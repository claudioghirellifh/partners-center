<?php

namespace App\Http\Controllers\AdminRoot;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Services\Impersonation\ImpersonationManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class ImpersonationController extends Controller
{
    public function __construct(private readonly ImpersonationManager $impersonation)
    {
    }

    public function store(Request $request, Company $company): RedirectResponse
    {
        /** @var User $root */
        $root = Auth::guard('root')->user();

        $adminUser = null;
        $adminId = $request->input('admin_user_id');
        if ($adminId) {
            $adminUser = $company->adminUsers()
                ->where('id', $adminId)
                ->where('is_active', true)
                ->first();

            if (! $adminUser) {
                return back()->withErrors(['impersonation' => 'Administrador inválido ou inativo.']);
            }
        }

        try {
            $this->impersonation->start($root, $company, $adminUser);
        } catch (RuntimeException $exception) {
            return back()->withErrors(['impersonation' => $exception->getMessage()]);
        }

        return redirect()->route('admin.dashboard', ['company' => $company])
            ->with('status', 'Modo empresa iniciado.');
    }

    public function destroy(): RedirectResponse
    {
        $this->impersonation->stop();

        return redirect()->route('adminroot.dashboard')->with('status', 'Você retornou ao painel Root.');
    }
}
