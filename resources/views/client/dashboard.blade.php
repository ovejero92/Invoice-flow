<x-panel-layout title="Hola, {{ auth()->user()->name }}" subtitle="Seguimiento de tus encargos">
    <div class="grid gap-6 sm:grid-cols-2 max-w-3xl">
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-amber-200/60 border border-amber-100/80">
            <p class="text-sm font-medium text-amber-800/80">Proyectos en curso</p>
            <p class="mt-3 text-4xl font-bold text-amber-950">{{ $proyectosEnCurso }}</p>
            <p class="mt-2 text-sm text-amber-900/70">Activos o pausados por tu proveedor.</p>
        </div>
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200/60">
            <p class="text-sm font-medium text-slate-500">Completados</p>
            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $proyectosCompletados }}</p>
            <p class="mt-2 text-sm text-slate-600">Historial de proyectos cerrados.</p>
        </div>
    </div>

    <div class="mt-10 rounded-2xl bg-gradient-to-r from-amber-600 to-orange-600 p-8 text-white shadow-lg max-w-3xl">
        <h2 class="text-lg font-semibold">¿Qué podés hacer acá?</h2>
        <p class="mt-2 text-sm text-amber-50 leading-relaxed">
            Revisá el avance de tus proyectos, descargá facturas en PDF y registrá pagos para mantener al día el estado de cobro.
        </p>
    </div>
</x-panel-layout>
