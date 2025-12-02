@php
    $company = $project->company;
    $customerName = $project->customer?->name ?? 'cliente';
    $primaryColor = $company?->brand_color ?? '#0F172A';
    $logo = $company?->logo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($company->logo_path) : null;
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Links de pagamento</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #f5f6fa;
            color: #1f2933;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
            overflow: hidden;
        }
        .header {
            padding: 32px;
            text-align: center;
            background: linear-gradient(135deg, {{ $primaryColor }}, {{ $primaryColor }}dd);
            color: #fff;
        }
        .header img {
            max-height: 60px;
            margin-bottom: 12px;
        }
        .content {
            padding: 32px;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 18px;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 6px 12px;
            border-radius: 9999px;
            background: rgba(15, 23, 42, 0.08);
            color: {{ $primaryColor }};
        }
        .btn-link {
            display: inline-block;
            margin-top: 16px;
            padding: 12px 20px;
            border-radius: 12px;
            background: {{ $primaryColor }};
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }
        .footer {
            padding: 20px 32px 32px 32px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($logo)
                <img src="{{ $logo }}" alt="{{ $company?->name }}">
            @endif
            <p>Olá {{ $customerName }},</p>
            <h2 style="margin: 8px 0 0; font-size: 22px; font-weight: 600;">Links de pagamento do projeto {{ $project->name }}</h2>
        </div>
        <div class="content">
            @foreach($links as $link)
                <div class="card">
                    <span class="badge">
                        {{ ($link['type'] ?? 'subscription') === 'charge' ? 'Cobrança avulsa' : 'Assinatura' }}
                    </span>
                    <p style="margin: 16px 0 0;">
                        {{ ($link['type'] ?? 'subscription') === 'charge'
                            ? ($link['label'] ?? 'Pagamento único disponível. Clique abaixo para concluir:')
                            : 'Clique no botão abaixo para concluir o pagamento deste ciclo:' }}
                    </p>
                    @if(!empty($link['message']))
                        <p style="margin: 12px 0 0; color: #475569;">{{ $link['message'] }}</p>
                    @endif
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="btn-link">
                        Abrir link de pagamento
                    </a>
                    <p style="margin-top: 12px; font-size: 13px; color: #64748b;">Se o botão não funcionar, copie e cole no navegador: <br> {{ $link['url'] }}</p>
                </div>
            @endforeach
            <p style="margin-top: 24px; color: #475569;">Qualquer dúvida é só responder este e-mail — estamos por aqui para ajudar.</p>
        </div>
        <div class="footer">
            {{ $company?->name ?? config('app.name') }} · {{ $company?->uri ?? url('/') }}
        </div>
    </div>
</body>
</html>
