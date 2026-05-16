@php
    $estadoLabel = fn (\App\Enums\ProyectoEstado $e) => match ($e) {
        \App\Enums\ProyectoEstado::Activo => 'En curso',
        \App\Enums\ProyectoEstado::Pausado => 'En curso',
        \App\Enums\ProyectoEstado::Completado => 'Completado',
        \App\Enums\ProyectoEstado::Archivado => 'Archivado',
    };
@endphp

<x-panel-layout title="Mis proyectos" subtitle="Lo que tenés activo con tus clientes">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3">Proyecto</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Estado cliente</th>
                    <th class="px-6 py-3 text-right">Tarifa</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($proyectos as $p)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <span class="font-medium text-slate-900">{{ $p->nombre }}</span>
                            <div class="text-xs text-slate-500">{{ $p->tareas_count }} tareas</div>
                        </td>
                        <td class="px-6 py-4 text-slate-700">{{ $p->cliente->nombre }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ in_array($p->estado, [\App\Enums\ProyectoEstado::Activo, \App\Enums\ProyectoEstado::Pausado], true) ? 'bg-teal-100 text-teal-900' : 'bg-slate-100 text-slate-700' }}">
                                {{ $estadoLabel($p->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono">{{ $p->moneda }} {{ number_format((float) $p->tarifa_hora, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('freelancer.proyectos.show', $p) }}" class="text-teal-700 font-semibold hover:underline">Abrir</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">Todavía no tenés proyectos asignados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $proyectos->links() }}</div>
</x-panel-layout>
