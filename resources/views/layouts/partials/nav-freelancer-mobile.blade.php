<div class="flex justify-around w-full text-[10px] text-slate-600">
    <a href="{{ route('freelancer.dashboard') }}" class="{{ request()->routeIs('freelancer.dashboard') ? 'text-teal-700 font-semibold' : '' }}">Panel</a>
    <a href="{{ route('freelancer.proyectos.index') }}" class="{{ request()->routeIs('freelancer.proyectos.*') ? 'text-teal-700 font-semibold' : '' }}">Proyectos</a>
    <a href="{{ route('freelancer.reporte-horas') }}" class="{{ request()->routeIs('freelancer.reporte-horas') ? 'text-teal-700 font-semibold' : '' }}">Reporte</a>
</div>
