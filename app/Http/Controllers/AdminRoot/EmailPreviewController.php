<?php

namespace App\Http\Controllers\AdminRoot;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\View\View;

class EmailPreviewController extends Controller
{
    public function index(): View
    {
        $templates = [
            [
                'key' => 'iugu-payment-links',
                'name' => 'Links de pagamento (Iugu)',
                'description' => 'E-mail enviado aos clientes com o link da assinatura e cobrança de setup.',
            ],
        ];

        return view('adminroot.emails.index', compact('templates'));
    }

    public function show(string $template): View
    {
        return match ($template) {
            'iugu-payment-links' => $this->iuguPaymentLinksPreview(),
            default => abort(404),
        };
    }

    protected function iuguPaymentLinksPreview(): View
    {
        $company = new Company([
            'name' => 'Partners Corp',
            'uri' => 'partners.example.com',
            'brand_color' => '#4F46E5',
            'logo_path' => null,
        ]);

        $customer = new Customer([
            'name' => 'Cliente Exemplo',
            'email' => 'cliente@example.com',
        ]);

        $project = new Project([
            'name' => 'Onboarding Pro',
            'client_email' => 'cliente@example.com',
        ]);

        $project->setRelation('company', $company);
        $project->setRelation('customer', $customer);

        $links = [
            ['label' => 'Assinatura mensal', 'url' => 'https://iugu.com/pay/assinatura123', 'type' => 'subscription'],
        ];

        return view('emails.iugu.payment-links', compact('project', 'links'));
    }
}
