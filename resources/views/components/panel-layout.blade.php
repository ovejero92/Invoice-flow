@props([
    'title',
    'subtitle' => null,
])

@php
    $roleKey = match (auth()->user()->role) {
        \App\Enums\UserRole::Admin => 'admin',
        \App\Enums\UserRole::Freelancer => 'freelancer',
        \App\Enums\UserRole::Client => 'client',
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-900" style="font-family: Figtree, ui-sans-serif, system-ui, sans-serif;">
    @if (\App\Support\ViteDevServer::hotFileExistsWithoutReachableServer())
        <div role="alert" style="background:#450a0a;color:#fecaca;padding:14px 18px;text-align:center;font-size:14px;line-height:1.5;border-bottom:3px solid #f87171;">
            <strong style="color:#fff;">Estilos no cargados:</strong>
            existe <code style="background:#7f1d1d;padding:2px 6px;border-radius:4px;">public/hot</code> (modo Vite), pero el servidor de Vite no está corriendo.
            <span style="display:block;margin-top:8px;">Solución: ejecutá <code style="background:#7f1d1d;padding:2px 6px;border-radius:4px;">npm run dev</code> en el proyecto, <em>o</em> borrá el archivo <code style="background:#7f1d1d;padding:2px 6px;border-radius:4px;">public/hot</code> y ejecutá <code style="background:#7f1d1d;padding:2px 6px;border-radius:4px;">npm run build</code>. Recargá la página.</span>
        </div>
    @endif
    <div class="min-h-screen flex">
        <aside class="hidden lg:flex w-64 flex-col border-r border-slate-200 bg-white shadow-sm">
            <div class="h-16 flex items-center px-6 border-b border-slate-100">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold text-slate-800 tracking-tight">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-white text-sm font-bold">IF</span>
                    <span>InvoiceFlow</span>
                </a>
            </div>
            @include('layouts.partials.nav-' . $roleKey)
            <div class="mt-auto p-4 border-t border-slate-100 text-xs text-slate-500">
                {{ auth()->user()->name }}
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8">
                <div>
                    <h1 class="text-lg font-semibold text-slate-900">{{ $title }}</h1>
                    @if ($subtitle)
                        <p class="text-sm text-slate-500">{{ $subtitle }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="text-sm text-slate-600 hover:text-indigo-600">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-slate-600 hover:text-rose-600">Salir</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-4 lg:p-8 pb-24 lg:pb-8 overflow-x-auto">
                @include('layouts.partials.flash')
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile nav -->
    <div class="lg:hidden fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 flex justify-around py-2 text-xs z-40">
        @include('layouts.partials.nav-' . $roleKey . '-mobile')
    </div>
</body>
</html>
