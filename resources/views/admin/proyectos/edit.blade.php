@php
    $estadoLabel = fn (\App\Enums\ProyectoEstado $e) => match ($e) {
        \App\Enums\ProyectoEstado::Activo => 'Activo',
        \App\Enums\ProyectoEstado::Pausado => 'Pausado',
        \App\Enums\ProyectoEstado::Completado => 'Completado',
        \App\Enums\ProyectoEstado::Archivado => 'Archivado',
    };
@endphp

<x-panel-layout title="Editar proyecto" subtitle="{{ $proyecto->nombre }}">
    <div class="max-w-2xl space-y-6">
        <form method="POST" action="{{ route('admin.proyectos.update', $proyecto) }}" class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200/60 space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700">Freelancer</label>
                <select name="user_id" required class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($freelancers as $f)
                        <option value="{{ $f->id }}" @selected(old('user_id', $proyecto->user_id) == $f->id)>{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Cliente</label>
                <select name="cliente_id" required class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($clientes as $c)
                        <option value="{{ $c->id }}" @selected(old('cliente_id', $proyecto->cliente_id) == $c->id)>{{ $c->nombre }} — {{ $c->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Nombre del proyecto</label>
                <input type="text" name="nombre" value="{{ old('nombre', $proyecto->nombre) }}" required class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Descripción</label>
                <textarea name="descripcion" rows="3" class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Estado</label>
                    <select name="estado" class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (\App\Enums\ProyectoEstado::cases() as $e)
                            <option value="{{ $e->value }}" @selected(old('estado', $proyecto->estado->value) === $e->value)>{{ $estadoLabel($e) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Moneda</label>
                    <input type="text" name="moneda" value="{{ old('moneda', $proyecto->moneda) }}" maxlength="8" required class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tarifa / hora</label>
                    <input type="number" step="0.01" min="0" name="tarifa_hora" value="{{ old('tarifa_hora', $proyecto->tarifa_hora) }}" required class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', optional($proyecto->fecha_inicio)->format('Y-m-d')) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Fin estimado</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin', optional($proyecto->fecha_fin)->format('Y-m-d')) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-500">Guardar</button>
                <a href="{{ route('admin.proyectos.show', $proyecto) }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Volver</a>
            </div>
        </form>

        <form action="{{ route('admin.proyectos.destroy', $proyecto) }}" method="POST" class="rounded-2xl border border-rose-200 bg-rose-50/50 p-6" onsubmit="return confirm('¿Eliminar proyecto completo?');">
            @csrf
            @method('DELETE')
            <p class="text-sm text-rose-900 mb-3">Zona peligrosa: se eliminan tareas y registros ligados.</p>
            <button type="submit" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-500">Eliminar proyecto</button>
        </form>
    </div>
</x-panel-layout>
