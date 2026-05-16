<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="InvoiceFlow — proyectos, horas y facturas para freelancers y equipos pequeños.">

    <title>InvoiceFlow — Proyectos y facturación</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { font-family: Figtree, ui-sans-serif, system-ui, sans-serif; margin: 0; background: #f1f5f9; color: #0f172a; }
            .box { max-width: 56rem; margin: 0 auto; padding: 1.5rem; }
            a { color: #4f46e5; }
        </style>
    @endif
</head>
<body class="min-h-screen antialiased bg-slate-100 text-slate-900" style="font-family: Figtree, ui-sans-serif, system-ui, sans-serif;">
    <header class="border-b border-slate-200 bg-white/90 backdrop-blur-sm sticky top-0 z-50">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold tracking-tight text-slate-800">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-sm font-bold text-white">IF</span>
                InvoiceFlow
            </a>
            @if (Route::has('login'))
                <nav class="flex items-center gap-2 text-sm">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-lg px-4 py-2 font-medium text-slate-600 hover:bg-slate-100 hover:text-indigo-600">Ir al panel</a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg px-4 py-2 font-medium text-slate-600 hover:bg-slate-100 hover:text-indigo-600">Iniciar sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-4 py-2 font-semibold text-white shadow-sm hover:bg-indigo-500">Crear cuenta</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main>
        <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Facturación y tiempo</p>
            <h1 class="mt-3 max-w-3xl text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                Tus proyectos, horas y facturas en un solo lugar
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-slate-600">
                InvoiceFlow está pensado para <strong>freelancers</strong> y <strong>equipos chicos</strong>: registrá tiempo, generá facturas por período, compartí un portal con tus clientes y descargá PDFs. El registro abierto crea una cuenta de <strong>freelancer</strong>; los administradores y el acceso cliente los configura quien opera el estudio.
            </p>
            @guest
                @if (Route::has('register'))
                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Empezar gratis
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                            Ya tengo cuenta
                        </a>
                    </div>
                @endif
            @endguest
        </section>

        <section class="border-y border-slate-200 bg-white py-16">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-center text-2xl font-bold text-slate-900">Tres formas de usar la plataforma</h2>
                <div class="mt-12 grid gap-8 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-6 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Administración</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">Clientes, proyectos, tareas facturables, emisión de facturas por fechas, pagos y alta de usuarios del portal cliente.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-6 shadow-sm ring-2 ring-indigo-100">
                        <h3 class="font-semibold text-slate-900">Freelancer</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">Registro de horas, vista de proyectos y reportes. Es el rol que obtenés al <strong>crear cuenta</strong> desde esta web.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-6 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Cliente</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">Solo lectura de proyectos, facturas en PDF y registro de pagos sobre facturas emitidas.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Qué podés hacer al entrar (hoy)</h2>
                    <ul class="mt-4 space-y-3 text-slate-600">
                        <li class="flex gap-2"><span class="text-indigo-600">✓</span> Gestionar clientes y proyectos con tareas y tarifas.</li>
                        <li class="flex gap-2"><span class="text-indigo-600">✓</span> Cargar horas y generar facturas con PDF.</li>
                        <li class="flex gap-2"><span class="text-indigo-600">✓</span> Dar de alta usuarios cliente y que registren pagos.</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6">
                    <h2 class="text-xl font-bold text-amber-950">Gratis ahora, evolución a futuro</h2>
                    <p class="mt-3 text-sm leading-relaxed text-amber-950/90">
                        Mientras desarrollás el producto, podés ofrecer el uso <strong>sin cargo</strong> para validar con usuarios reales. Más adelante podés sumar planes de pago (por ejemplo límites de facturas, usuarios o funciones avanzadas como impuestos legales o multi-equipo); eso implica pasarela de cobro y términos legales propios.
                    </p>
                    <p class="mt-4 text-xs text-amber-900/80">
                        El botón “Deploy now” que traía Laravel era solo un enlace a servicios de despliegue del ecosistema Laravel, no un deploy automático a Fly.io ni a otros hosts. Para publicar la app seguí la guía de tu proveedor (build, variables de entorno, base de datos).
                    </p>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white py-8 text-center text-sm text-slate-500">
        <p>{{ config('app.name', 'InvoiceFlow') }} · Hecho con Laravel</p>
    </footer>
</body>
</html>
