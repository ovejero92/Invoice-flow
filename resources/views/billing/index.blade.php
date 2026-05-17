<x-panel-layout title="Tu plan" subtitle="Uso del estudio y límites">
    <div class="max-w-3xl space-y-6">
        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ session('error') }}</div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Estudio</p>
            <p class="text-xl font-semibold text-slate-900">{{ $organization->name }}</p>
            <p class="mt-2 inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $organization->isPro() ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-700' }}">
                Plan {{ $organization->isPro() ? 'Pro' : 'Gratis' }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="font-semibold text-slate-900">Uso actual</h2>
            <ul class="mt-4 space-y-2 text-sm text-slate-600">
                <li>Clientes: <strong>{{ $usage['clients'] }}</strong> / {{ $limits['max_clients'] }}</li>
                <li>Proyectos: <strong>{{ $usage['projects'] }}</strong> / {{ $limits['max_projects'] }}</li>
                <li>Facturas este mes: <strong>{{ $usage['invoices_this_month'] }}</strong> / {{ $limits['max_invoices_per_month'] }}</li>
                <li>Usuarios portal cliente: <strong>{{ $usage['client_portal_users'] }}</strong> / {{ $limits['max_client_portal_users'] }}</li>
            </ul>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="font-semibold">Gratis</h3>
                <p class="mt-1 text-2xl font-bold">0 €</p>
                <p class="mt-2 text-sm text-slate-600">Ideal para probar y primeros clientes.</p>
            </div>
            <div class="rounded-2xl border-2 border-indigo-200 bg-indigo-50/50 p-5">
                <h3 class="font-semibold text-indigo-900">Pro</h3>
                <p class="mt-1 text-2xl font-bold text-indigo-900">~{{ $pricing['pro']['price_eur'] }} €/mes</p>
                <p class="mt-2 text-sm text-indigo-800">Más clientes, facturas, export CSV y datos fiscales completos.</p>
                @unless ($organization->isPro())
                    <p class="mt-4 text-xs text-indigo-700">Por ahora el upgrade es manual: contactá al administrador de la plataforma.</p>
                @endunless
            </div>
        </div>

        @if (auth()->user()->isFreelancer())
            <p class="text-sm text-slate-500">
                <a href="{{ route('freelancer.organization.settings') }}" class="text-indigo-600 hover:underline">Datos fiscales del estudio</a>
            </p>
        @endif
    </div>
</x-panel-layout>
