<div class="flex justify-around w-full text-[10px] text-center text-slate-600">
    <a href="{{ route('admin.dashboard') }}" class="px-1 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 font-semibold' : '' }}">Inicio</a>
    <a href="{{ route('admin.clientes.index') }}" class="px-1 {{ request()->routeIs('admin.clientes.*') ? 'text-indigo-600 font-semibold' : '' }}">Clientes</a>
    <a href="{{ route('admin.proyectos.index') }}" class="px-1 {{ request()->routeIs('admin.proyectos.*') ? 'text-indigo-600 font-semibold' : '' }}">Proyectos</a>
    <a href="{{ route('admin.facturas.index') }}" class="px-1 {{ request()->routeIs('admin.facturas.*') ? 'text-indigo-600 font-semibold' : '' }}">Facturas</a>
</div>
