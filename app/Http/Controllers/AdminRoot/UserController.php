<?php

namespace App\Http\Controllers\AdminRoot;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRoot\AdminUserStoreRequest;
use App\Http\Requests\AdminRoot\AdminUserUpdateRequest;
use App\Mail\AdminWelcomeMail;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->with(['company'])
            ->latest('id')
            ->paginate(15);

        return view('adminroot.users.index', compact('users'));
    }

    public function create(): View
    {
        $companies = Company::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('adminroot.users.create', compact('companies'));
    }

    public function store(AdminUserStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $company = Company::query()->findOrFail($data['company_id']);

        $user = new User();
        $user->company_id = (int) $data['company_id'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = User::ROLE_ADMIN;
        $user->is_active = true;
        $tempPassword = Str::random(12);
        $user->password = Hash::make($tempPassword);
        $user->save();

        $loginUrl = route('admin.login.form', ['company' => $company]);
        Mail::to($user->email)->send(new AdminWelcomeMail($company, $user, $tempPassword, $loginUrl));

        return redirect()->route('adminroot.users.index')
            ->with('status', 'Admin criado e instruções enviadas por e-mail.');
    }

    public function edit(User $user): View
    {
        return view('adminroot.users.edit', compact('user'));
    }

    public function update(AdminUserUpdateRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Proteções mínimas
        if ($user->isRoot() && ($data['is_active'] ?? true) === false) {
            return back()->withErrors(['user' => 'Não é possível inativar o usuário root.']);
        }

        $emailChanged = strcasecmp($data['email'], $user->email) !== 0;

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->is_active = (bool) $data['is_active'];

        $tempPassword = null;

        if ($emailChanged && ! $user->isRoot()) {
            // Define uma senha temporária e envia e-mail com instruções
            $tempPassword = Str::random(12);
            $user->password = Hash::make($tempPassword);
        }

        $user->save();

        if ($emailChanged && ! $user->isRoot()) {
            $company = $user->company; // pode ser null
            $loginUrl = $company && $company->uri
                ? route('admin.login.form', ['company' => $company])
                : url('/');

            Mail::to($user->email)->send(new AdminWelcomeMail(
                $company ?? new \App\Models\Company(['name' => config('app.name')]),
                $user,
                $tempPassword,
                $loginUrl,
            ));
        }

        return redirect()->route('adminroot.users.index')
            ->with('status', 'Usuário atualizado com sucesso.'.($emailChanged && ! $user->isRoot() ? ' Enviamos novas instruções para o e-mail atualizado.' : ''));
    }

    public function destroy(User $user): RedirectResponse
    {
        $current = Auth::guard('root')->user();

        if ($user->isRoot() || ($current && $current->id === $user->id)) {
            return back()->withErrors(['user' => 'Não é possível excluir este usuário.']);
        }

        $user->delete();

        return redirect()->route('adminroot.users.index')
            ->with('status', 'Usuário excluído com sucesso.');
    }
}
