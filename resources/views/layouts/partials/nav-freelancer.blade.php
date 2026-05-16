@php
    $active = fn (string $pattern) => request()->routeIs($pattern)
        ? 'border-teal-600 bg-teal-50 text-teal-900'
        : 'border-transparent text-slate-600 hover:bg-slate-50';
@endphp

<nav class="flex-1 py-4 space-y-1 px-3 text-sm font-medium">
    <a href="{{ route('freelancer.dashboard') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('freelancer.dashboard') }}">Mi panel</a>
    <a href="{{ route('freelancer.proyectos.index') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('freelancer.proyectos.*') }}">Mis proyectos</a>
    <a href="{{ route('freelancer.reporte-horas') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('freelancer.reporte-horas') }}">Reporte de horas</a>
</nav>
