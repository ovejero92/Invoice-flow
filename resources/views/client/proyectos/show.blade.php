@php
    $tareaEstado = fn (\App\Enums\TareaEstado $e) => match ($e) {
        \App\Enums\TareaEstado::Pendiente => 'Pendiente',
        \App\Enums\TareaEstado::EnProgreso => 'En progreso',
        \App\Enums\TareaEstado::Completada => 'Completada',
        \App\Enums\TareaEstado::Cancelada => 'Cancelada',
    };
@endphp

<x-panel-layout title="{{ $proyecto->nombre }}" subtitle="Detalle para tu revisión">
    <div class="mb-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 max-w-3xl">
        <p class="text-sm text-slate-600 leading-relaxed">{{ $proyecto->descripcion ?: 'Tu proveedor aún no cargó una descripción pública.' }}</p>
    </div>

    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-4">Tareas</h3>
    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60 divide-y divide-slate-100 max-w-3xl">
        @forelse ($proyecto->tareas as $t)
            <div class="px-6 py-4 flex justify-between gap-4">
                <div>
                    <p class="font-medium text-slate-900">{{ $t->titulo }}</p>
                    @if ($t->descripcion)
                        <p class="mt-1 text-sm text-slate-500">{{ $t->descripcion }}</p>
                    @endif
                </div>
                <span class="text-xs font-medium text-slate-600 shrink-0">{{ $tareaEstado($t->estado) }}</span>
            </div>
        @empty
            <p class="px-6 py-10 text-center text-slate-500">Sin tareas publicadas.</p>
        @endforelse
    </div>

    <div class="mt-8">
        <a href="{{ route('client.proyectos.index') }}" class="text-sm font-semibold text-amber-800 hover:underline">← Volver a proyectos</a>
    </div>
</x-panel-layout>
