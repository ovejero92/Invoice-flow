@php
    $estadoLabel = fn (\App\Enums\ProyectoEstado $e) => match ($e) {
        \App\Enums\ProyectoEstado::Activo => 'Activo',
        \App\Enums\ProyectoEstado::Pausado => 'Pausado',
        \App\Enums\ProyectoEstado::Completado => 'Completado',
        \App\Enums\ProyectoEstado::Archivado => 'Archivado',
    };
@endphp

<x-panel-layout title="Proyectos" subtitle="Seguimiento y tarifas">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-slate-600">Tarifa por hora por proyecto. Las tareas heredan facturación según registros.</p>
        <a href="{{ route('admin.proyectos.create') }}" class="inline-flex justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">Nuevo proyecto</a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3">Proyecto</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Freelancer</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3 text-right">Tarifa / h</th>
                    <th class="px-6 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($proyectos as $p)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">{{ $p->nombre }}</div>
                            <div class="text-xs text-slate-500">{{ $p->tareas_count }} tareas</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $p->cliente->nombre }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $p->user->name }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">{{ $estadoLabel($p->estado) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono text-slate-800">{{ $p->moneda }} {{ number_format((float) $p->tarifa_hora, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.proyectos.show', $p) }}" class="text-indigo-600 hover:underline">Abrir</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">Sin proyectos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $proyectos->links() }}</div>
</x-panel-layout>
