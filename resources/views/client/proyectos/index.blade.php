@php
    $estadoLabel = fn (\App\Enums\ProyectoEstado $e) => match ($e) {
        \App\Enums\ProyectoEstado::Activo, \App\Enums\ProyectoEstado::Pausado => 'En curso',
        \App\Enums\ProyectoEstado::Completado => 'Completado',
        \App\Enums\ProyectoEstado::Archivado => 'Archivado',
    };
@endphp

<x-panel-layout title="Mis proyectos" subtitle="Estado de cada encargo">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($proyectos as $p)
            <a href="{{ route('client.proyectos.show', $p) }}" class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 hover:ring-amber-300 hover:shadow-md transition">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="font-semibold text-slate-900 group-hover:text-amber-900">{{ $p->nombre }}</h3>
                    <span class="shrink-0 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-900">{{ $estadoLabel($p->estado) }}</span>
                </div>
                <p class="mt-2 text-sm text-slate-500">{{ $p->tareas_count }} tareas</p>
            </a>
        @empty
            <p class="text-slate-500 col-span-full py-12 text-center rounded-2xl bg-white ring-1 ring-slate-200/60">No tenés proyectos asignados.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $proyectos->links() }}</div>
</x-panel-layout>
