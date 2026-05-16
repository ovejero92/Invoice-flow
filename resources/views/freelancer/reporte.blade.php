<x-panel-layout title="Reporte de horas" subtitle="Filtrá por periodo">
    <form method="GET" action="{{ route('freelancer.reporte-horas') }}" class="mb-8 flex flex-wrap gap-4 items-end rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60">
        <div>
            <label class="text-xs font-medium text-slate-500 uppercase">Desde</label>
            <input type="date" name="desde" value="{{ $desde }}" class="mt-1 rounded-xl border-slate-300 text-sm">
        </div>
        <div>
            <label class="text-xs font-medium text-slate-500 uppercase">Hasta</label>
            <input type="date" name="hasta" value="{{ $hasta }}" class="mt-1 rounded-xl border-slate-300 text-sm">
        </div>
        <button type="submit" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">Aplicar</button>
    </form>

    <div class="grid gap-6 lg:grid-cols-3 mb-8">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 lg:col-span-1">
            <h3 class="text-sm font-semibold text-slate-900">Total periodo</h3>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format((float) $totalHoras, 2, ',', '.') }} h</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 lg:col-span-2">
            <h3 class="text-sm font-semibold text-slate-900 mb-4">Por proyecto</h3>
            <ul class="space-y-2 text-sm">
                @forelse ($totalesPorProyecto as $row)
                    <li class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-700">{{ $row['proyecto'] }}</span>
                        <span class="font-mono">{{ number_format((float) $row['horas'], 2, ',', '.') }} h</span>
                    </li>
                @empty
                    <li class="text-slate-500">Sin datos en el rango.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Proyecto</th>
                    <th class="px-6 py-3">Tarea</th>
                    <th class="px-6 py-3 text-right">Horas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($registros as $r)
                    <tr>
                        <td class="px-6 py-3 text-slate-600">{{ $r->fecha->format('d/m/Y') }}</td>
                        <td class="px-6 py-3">{{ $r->tarea->proyecto->cliente->nombre }}</td>
                        <td class="px-6 py-3">{{ $r->tarea->proyecto->nombre }}</td>
                        <td class="px-6 py-3">{{ $r->tarea->titulo }}</td>
                        <td class="px-6 py-3 text-right font-mono">{{ number_format((float) $r->horas, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Sin registros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-panel-layout>
