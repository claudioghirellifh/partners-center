<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="x-apple-disable-message-reformatting">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bem-vindo(a)</title>
        <style>
            .btn { display:inline-block; padding:10px 16px; border-radius:8px; background:#F27327; color:#fff; text-decoration:none; font-weight:600; }
            .muted { color:#64748b; font-size:12px; }
            code { background:#f1f5f9; color:#0f172a; padding:2px 6px; border-radius:4px; }
        </style>
    </head>
    <body style="margin:0; padding:24px; font-family:ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, Noto Sans, 'Apple Color Emoji', 'Segoe UI Emoji'; color:#0f172a;">
        <div style="max-width:640px; margin:0 auto;">
            <div style="margin-bottom:16px;">
                @php
                    $logoUrl = $company->logo_path
                        ? asset('storage/'.ltrim($company->logo_path, '/'))
                        : asset('brand/logo-light.png');
                @endphp
                <img src="{{ $logoUrl }}" alt="{{ $company->logo_path ? $company->name : config('app.name') }}" style="max-height:60px; height:auto; width:auto; display:block;" />
            </div>
            <h1 style="margin:0 0 12px 0; font-size:22px;">Bem-vindo(a) como Admin — {{ $company->name }}</h1>
            <p style="margin:0 0 12px 0; color:#334155;">Você foi configurado(a) como administrador(a) da empresa <strong>{{ $company->name }}</strong>.</p>

            <p style="margin:16px 0 8px 0;">Acesse o painel administrativo pelo link abaixo:</p>
            <p style="margin:0 0 16px 0;"><a class="btn" href="{{ $loginUrl }}" target="_blank" rel="noopener">Entrar no painel</a></p>

            <p style="margin:16px 0 8px 0;">Suas credenciais iniciais:</p>
            <ul style="margin:0 0 16px 18px; padding:0;">
                <li style="margin-bottom:6px;">E-mail: <code>{{ $admin->email }}</code></li>
                <li>Senha temporária: <code>{{ $temporaryPassword }}</code></li>
            </ul>

            <p style="margin:16px 0; color:#334155;">Por segurança, ao entrar, altere sua senha.</p>

            <p class="muted" style="margin-top:28px;">Se você não esperava este e-mail, pode ignorá-lo com segurança.</p>

            <p class="muted" style="margin-top:4px;">&copy; {{ now()->year }} {{ config('app.name') }}</p>
        </div>
    </body>
    </html>
