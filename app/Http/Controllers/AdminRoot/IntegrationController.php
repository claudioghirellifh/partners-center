<?php

namespace App\Http\Controllers\AdminRoot;

use App\Http\Controllers\Controller;
use App\Repositories\SettingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntegrationController extends Controller
{
    public function __construct(private readonly SettingRepository $settings)
    {
    }

    public function index(): View
    {
        $iuguToken = $this->settings->get('integrations.iugu', 'api_token');

        return view('adminroot.integrations.index', compact('iuguToken'));
    }

    public function updateIugu(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_token' => ['nullable', 'string', 'max:255'],
        ]);

        $this->settings->set('integrations.iugu', 'api_token', $validated['api_token'] ?? null);

        return redirect()->route('adminroot.integrations.index')
            ->with('status', 'Token da Iugu atualizado com sucesso.');
    }
}
