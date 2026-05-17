@php
    $active = fn (string $pattern) => request()->routeIs($pattern)
        ? 'border-indigo-600 bg-indigo-50 text-indigo-800'
        : 'border-transparent text-slate-600 hover:bg-slate-50';
@endphp

<nav class="flex-1 py-4 space-y-1 px-3 text-sm font-medium">
    <a href="{{ route('admin.dashboard') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('admin.dashboard') }}">Panel</a>
    <a href="{{ route('admin.clientes.index') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('admin.clientes.*') }}">Clientes</a>
    <a href="{{ route('admin.proyectos.index') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('admin.proyectos.*') }}">Proyectos</a>
    <a href="{{ route('admin.facturas.index') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('admin.facturas.*') }}">Facturación</a>
    <a href="{{ route('admin.pagos.index') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('admin.pagos.*') }}">Pagos</a>
    <a href="{{ route('admin.organizations.index') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('admin.organizations.*') }}">Estudios / planes</a>
    <a href="{{ route('admin.usuarios-cliente.create') }}" class="block rounded-md border-l-4 px-3 py-2 {{ $active('admin.usuarios-cliente.*') }}">Alta portal cliente</a>
</nav>
