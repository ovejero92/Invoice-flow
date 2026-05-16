<x-panel-layout title="Generar factura" subtitle="Consolidá horas por proyecto y rango de fechas">
    <form method="POST" action="{{ route('admin.facturas.store') }}" class="max-w-2xl space-y-6 rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200/60">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700">Proyecto</label>
            <select name="proyecto_id" required class="mt-1 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach ($proyectos as $p)
                    <option value="{{ $p->id }}" @selected((string) old('proyecto_id', $proyectoPre) === (string) $p->id)>
                        {{ $p->nombre }} — {{ $p->cliente->nombre }} ({{ $p->user->name }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">Periodo desde</label>
                <input type="date" name="periodo_desde" value="{{ old('periodo_desde', now()->startOfMonth()->toDateString()) }}" required class="mt-1 w-full rounded-xl border-slate-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Periodo hasta</label>
                <input type="date" name="periodo_hasta" value="{{ old('periodo_hasta', now()->endOfMonth()->toDateString()) }}" required class="mt-1 w-full rounded-xl border-slate-300 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">IVA / impuesto %</label>
            <input type="number" step="0.01" min="0" max="100" name="impuesto_pct" value="{{ old('impuesto_pct', '21') }}" required class="mt-1 w-full max-w-xs rounded-xl border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Notas (opcional)</label>
            <textarea name="notas" rows="3" class="mt-1 w-full rounded-xl border-slate-300 text-sm">{{ old('notas') }}</textarea>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-500">Calcular y emitir</button>
            <a href="{{ route('admin.facturas.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</x-panel-layout>
