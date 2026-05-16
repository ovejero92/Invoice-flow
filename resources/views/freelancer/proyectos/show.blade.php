@php
    $tareaEstado = fn (\App\Enums\TareaEstado $e) => match ($e) {
        \App\Enums\TareaEstado::Pendiente => 'Pendiente',
        \App\Enums\TareaEstado::EnProgreso => 'En progreso',
        \App\Enums\TareaEstado::Completada => 'Completada',
        \App\Enums\TareaEstado::Cancelada => 'Cancelada',
    };
@endphp

<x-panel-layout title="{{ $proyecto->nombre }}" subtitle="{{ $proyecto->cliente->nombre }}">
    <p class="mb-6 text-sm text-slate-600 max-w-3xl">{{ $proyecto->descripcion ?: 'Sin descripción cargada.' }}</p>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-semibold text-slate-900">Tareas</h3>
            <span class="text-xs text-slate-500">Solo lectura — el alta la hace administración</span>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($proyecto->tareas as $t)
                <li class="px-6 py-4 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="font-medium text-slate-900">{{ $t->titulo }}</p>
                        <p class="text-xs text-slate-500">{{ $tareaEstado($t->estado) }} @if($t->facturable) · Facturable @else · No facturable @endif</p>
                    </div>
                </li>
            @empty
                <li class="px-6 py-10 text-center text-slate-500">No hay tareas.</li>
            @endforelse
        </ul>
    </div>

    <div class="rounded-2xl bg-teal-50 border border-teal-100 p-6">
        <p class="text-sm text-teal-900">Para cargar horas usá el temporizador en <a href="{{ route('freelancer.dashboard') }}" class="font-semibold underline">Tu panel</a>.</p>
    </div>
</x-panel-layout>
