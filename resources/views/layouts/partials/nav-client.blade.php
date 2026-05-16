@php
    $active = fn (string $pattern) => request()->routeIs($pattern)
        ? 'border-amber-500 bg-amber-50 text-amber-950'
        : 'border-transparent text-slate-600 hover:bg-slate-50';
@endphp

<nav class="flex-1 py-4 space-y-1 px-3 text-sm font-medium">
    <a href="{{ route('client.dashboard') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('client.dashboard') }}">Inicio</a>
    <a href="{{ route('client.proyectos.index') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('client.proyectos.*') }}">Mis proyectos</a>
    <a href="{{ route('client.facturas.index') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('client.facturas.*') }}">Facturas</a>
</nav>
