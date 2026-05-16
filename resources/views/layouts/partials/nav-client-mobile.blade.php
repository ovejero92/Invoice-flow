<div class="flex justify-around w-full text-[10px] text-slate-600">
    <a href="{{ route('client.dashboard') }}" class="{{ request()->routeIs('client.dashboard') ? 'text-amber-800 font-semibold' : '' }}">Inicio</a>
    <a href="{{ route('client.proyectos.index') }}" class="{{ request()->routeIs('client.proyectos.*') ? 'text-amber-800 font-semibold' : '' }}">Proyectos</a>
    <a href="{{ route('client.facturas.index') }}" class="{{ request()->routeIs('client.facturas.*') ? 'text-amber-800 font-semibold' : '' }}">Facturas</a>
</div>
