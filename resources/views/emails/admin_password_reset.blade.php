<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="x-apple-disable-message-reformatting">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Redefinição de senha</title>
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">
        <style>
            .btn { display:inline-block; padding:10px 16px; border-radius:8px; background:#F27327; color:#fff; text-decoration:none; font-weight:600; }
            .muted { color:#64748b; font-size:12px; }
            .wrap { max-width:640px; margin:0 auto; }
            .box { border:1px solid #e2e8f0; background:#ffffff; border-radius:16px; padding:24px; }
        </style>
    </head>
    <body style="margin:0; padding:24px; background:#ffffff; font-family:ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, Noto Sans, 'Apple Color Emoji', 'Segoe UI Emoji'; color:#0f172a;">
        <?php $brand = $company->brand_color ?? '#F27327'; ?>
        <div class="wrap">
            <div style="margin:0 0 16px 0;">
                <?php if ($company->logo_path): ?>
                    <img src="{{ Storage::disk('public')->url($company->logo_path) }}" alt="{{ $company->name }}" style="height:40px; width:auto; display:block;" />
                <?php else: ?>
                    <div style="display:inline-block; padding:8px 12px; border-radius:10px; background: {{ $brand }}; color:#fff; font-weight:700;">
                        {{ str($company->name)->substr(0,2)->upper() }}
                    </div>
                <?php endif; ?>
            </div>

            <div class="box">
                <h1 style="margin:0 0 12px 0; font-size:22px;">Redefinição de senha</h1>
                <p style="margin:0 0 12px 0; color:#334155;">Recebemos uma solicitação para redefinir a senha da sua conta de administrador em <strong>{{ $company->name }}</strong>.</p>

                <p style="margin:16px 0 8px 0;">Para continuar, clique no botão abaixo:</p>
                <p style="margin:0 0 16px 0;">
                    <a class="btn" href="{{ $url }}" target="_blank" rel="noopener" style="background: {{ $brand }};">Redefinir senha</a>
                </p>

                <p style="margin:0 0 12px 0; color:#334155;">Este link expira em {{ $expire }} minutos.</p>

                <p class="muted" style="margin-top:24px;">Se você não solicitou esta redefinição, você pode ignorar este e-mail com segurança.</p>
                <p class="muted" style="margin-top:4px;">&copy; {{ now()->year }} {{ $company->name }}</p>
            </div>
        </div>
    </body>
    </html>
