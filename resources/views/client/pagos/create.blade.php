<x-panel-layout title="Registrar pago" subtitle="Factura {{ $factura->numero }}">
    <div class="max-w-lg rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200/60 mb-6">
        <p class="text-sm text-slate-600">Saldo pendiente estimado:</p>
        <p class="text-2xl font-bold font-mono text-amber-950 mt-1">{{ number_format($factura->saldoPendiente(), 2, ',', '.') }}</p>
    </div>

    <form method="POST" action="{{ route('client.pagos.store', $factura) }}" class="max-w-lg space-y-5 rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200/60">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700">Monto</label>
            <input type="number" step="0.01" min="0.01" name="monto" value="{{ old('monto', $factura->saldoPendiente()) }}" required class="mt-1 w-full rounded-xl border-slate-300 text-sm font-mono">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Fecha del pago</label>
            <input type="date" name="fecha_pago" value="{{ old('fecha_pago', now()->toDateString()) }}" required class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Método</label>
            <select name="metodo" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
                @foreach (['transferencia', 'tarjeta', 'efectivo', 'otro'] as $m)
                    <option value="{{ $m }}" @selected(old('metodo') === $m)>{{ ucfirst($m) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Notas</label>
            <textarea name="notas" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm" placeholder="Referencia, banco, etc.">{{ old('notas') }}</textarea>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-500">Confirmar pago</button>
            <a href="{{ route('client.facturas.show', $factura) }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</x-panel-layout>
