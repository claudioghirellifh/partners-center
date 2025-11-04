<?php

namespace App\Http\Controllers\AdminRoot;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('adminroot.dashboard', [
            'summaries' => [
                [
                    'title' => 'Empresas ativas',
                    'value' => '0',
                    'subtitle' => 'Em breve exibiremos estatísticas reais.',
                ],
                [
                    'title' => 'Admins criados',
                    'value' => '0',
                    'subtitle' => 'Cadastro de admins será habilitado nas próximas etapas.',
                ],
                [
                    'title' => 'Sellers ativos',
                    'value' => '0',
                    'subtitle' => 'Módulo de vendedores em desenvolvimento.',
                ],
            ],
        ]);
    }
}
