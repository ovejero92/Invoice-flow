@php
    $estadoLabel = fn (\App\Enums\ProyectoEstado $e) => match ($e) {
        \App\Enums\ProyectoEstado::Activo => 'Activo',
        \App\Enums\ProyectoEstado::Pausado => 'Pausado',
        \App\Enums\ProyectoEstado::Completado => 'Completado',
        \App\Enums\ProyectoEstado::Archivado => 'Archivado',
    };
    $tareaEstado = fn (\App\Enums\TareaEstado $e) => match ($e) {
        \App\Enums\TareaEstado::Pendiente => 'Pendiente',
        \App\Enums\TareaEstado::EnProgreso => 'En progreso',
        \App\Enums\TareaEstado::Completada => 'Completada',
        \App\Enums\TareaEstado::Cancelada => 'Cancelada',
    };
@endphp

<x-panel-layout title="{{ $proyecto->nombre }}" subtitle="Tareas y facturación">
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <a href="{{ route('admin.proyectos.edit', $proyecto) }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Editar proyecto</a>
        <a href="{{ route('admin.facturas.create', ['proyecto_id' => $proyecto->id]) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">Generar factura</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3 mb-8">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 lg:col-span-1">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide">Resumen</h3>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Cliente</dt><dd class="font-medium text-slate-900 text-right">{{ $proyecto->cliente->nombre }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Freelancer</dt><dd class="text-slate-900 text-right">{{ $proyecto->user->name }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Estado</dt><dd class="text-slate-900 text-right">{{ $estadoLabel($proyecto->estado) }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Tarifa</dt><dd class="font-mono text-slate-900 text-right">{{ $proyecto->moneda }} {{ number_format((float) $proyecto->tarifa_hora, 2, ',', '.') }}</dd></div>
            </dl>
            @if ($proyecto->descripcion)
                <p class="mt-4 text-sm text-slate-600 leading-relaxed">{{ $proyecto->descripcion }}</p>
            @endif
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 lg:col-span-2">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-4">Nueva tarea</h3>
            <form action="{{ route('admin.proyectos.tareas.store', $proyecto) }}" method="POST" class="grid gap-4 sm:grid-cols-2">
                @csrf
                <div class="sm:col-span-2">
                    <input type="text" name="titulo" placeholder="Título" required class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="sm:col-span-2">
                    <textarea name="descripcion" rows="2" placeholder="Descripción (opcional)" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <select name="estado" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (\App\Enums\TareaEstado::cases() as $e)
                            <option value="{{ $e->value }}">{{ $tareaEstado($e) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="number" name="orden" value="0" min="0" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Orden">
                </div>
                <label class="sm:col-span-2 flex items-center gap-2 text-sm text-slate-700">
                    <input type="hidden" name="facturable" value="0">
                    <input type="checkbox" name="facturable" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    Facturable (incluir en futuras facturas)
                </label>
                <div class="sm:col-span-2">
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Añadir tarea</button>
                </div>
            </form>
        </div>
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-900">Tareas</h3>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse ($proyecto->tareas as $t)
                <div class="p-6 space-y-4">
                    <form method="POST" action="{{ route('admin.tareas.update', $t) }}" class="grid gap-4 lg:grid-cols-12 lg:items-end">
                        @csrf
                        @method('PATCH')
                        <div class="lg:col-span-4">
                            <label class="text-xs text-slate-500">Título</label>
                            <input type="text" name="titulo" value="{{ $t->titulo }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
                        </div>
                        <div class="lg:col-span-3">
                            <label class="text-xs text-slate-500">Estado</label>
                            <select name="estado" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
                                @foreach (\App\Enums\TareaEstado::cases() as $e)
                                    <option value="{{ $e->value }}" @selected($t->estado === $e)>{{ $tareaEstado($e) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="text-xs text-slate-500">Orden</label>
                            <input type="number" name="orden" value="{{ $t->orden }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
                        </div>
                        <div class="lg:col-span-2 flex items-end pb-2">
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="hidden" name="facturable" value="0">
                                <input type="checkbox" name="facturable" value="1" @checked($t->facturable) class="rounded border-slate-300 text-indigo-600">
                                Facturable
                            </label>
                        </div>
                        <div class="lg:col-span-1 flex items-end justify-end gap-2 pb-1">
                            <button type="submit" class="text-sm font-semibold text-indigo-600 hover:underline">Guardar</button>
                        </div>
                    </form>
                    <div class="flex justify-end">
                        <form action="{{ route('admin.tareas.destroy', $t) }}" method="POST" onsubmit="return confirm('¿Eliminar tarea?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-rose-600 hover:underline">Eliminar tarea</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="p-10 text-center text-slate-500">No hay tareas. Creá la primera arriba.</p>
            @endforelse
        </div>
    </div>
</x-panel-layout>
