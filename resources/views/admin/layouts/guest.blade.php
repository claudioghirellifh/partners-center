<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full {{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $company->name }} · Admin Login</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @if($company->favicon_path)
            <link rel="icon" type="image/png" href="{{ Storage::disk('public')->url($company->favicon_path) }}">
        @endif
    </head>
    <body class="flex min-h-screen flex-col bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100" style="--brand: {{ $company->brand_color ?? '#F27327' }};">
        <main class="flex flex-1 flex-col justify-center px-6 py-12">
            <div class="mx-auto flex w-full max-w-md flex-col gap-8">
                <div class="flex justify-end">@livewire('theme-toggle')</div>
                @yield('content')
            </div>
        </main>
        @livewireStyles
        @livewireScripts
    </body>
    </html>

