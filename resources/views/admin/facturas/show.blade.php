@php
    use App\Enums\FacturaEstado;
    $estadoEtiqueta = fn (FacturaEstado $e) => match ($e) {
        FacturaEstado::Borrador => 'Borrador',
        FacturaEstado::Emitida => 'Emitida',
        FacturaEstado::Pagada => 'Pagada',
        FacturaEstado::Anulada => 'Anulada',
    };
@endphp

<x-panel-layout title="Factura {{ $factura->numero }}" subtitle="{{ $factura->cliente->nombre }}">
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('facturas.pdf', $factura) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">Descargar PDF</a>
        <a href="{{ route('admin.facturas.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Volver al listado</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 lg:col-span-1">
            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Totales</h3>
            <dl class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Subtotal</dt><dd class="font-mono">{{ number_format((float) $factura->subtotal, 2, ',', '.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Impuesto ({{ number_format((float) $factura->impuesto_pct, 2, ',', '.') }}%)</dt><dd class="font-mono">{{ number_format((float) $factura->impuesto_importe, 2, ',', '.') }}</dd></div>
                <div class="flex justify-between font-semibold text-slate-900 pt-2 border-t border-slate-100"><dt>Total</dt><dd class="font-mono">{{ number_format((float) $factura->total, 2, ',', '.') }}</dd></div>
                <div class="flex justify-between text-emerald-700"><dt>Pagado</dt><dd class="font-mono">{{ number_format($factura->montoPagado(), 2, ',', '.') }}</dd></div>
                <div class="flex justify-between text-amber-800"><dt>Pendiente</dt><dd class="font-mono">{{ number_format($factura->saldoPendiente(), 2, ',', '.') }}</dd></div>
            </dl>
            <p class="mt-4 text-sm"><span class="text-slate-500">Estado:</span> {{ $estadoEtiqueta($factura->estado) }}</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 lg:col-span-2">
            <h3 class="font-semibold text-slate-900 mb-4">Detalle de horas facturadas</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-xs text-slate-500 uppercase">
                        <tr>
                            <th class="py-2 pr-4">Fecha</th>
                            <th class="py-2 pr-4">Tarea</th>
                            <th class="py-2 pr-4">Horas</th>
                            <th class="py-2 text-right">Importe</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($factura->registrosHoras as $r)
                            @php $tarifa = (float) $r->tarea->proyecto->tarifa_hora; $imp = (float) $r->horas * $tarifa; @endphp
                            <tr>
                                <td class="py-2 pr-4 text-slate-600">{{ $r->fecha->format('d/m/Y') }}</td>
                                <td class="py-2 pr-4">{{ $r->tarea->titulo }}</td>
                                <td class="py-2 pr-4 font-mono">{{ number_format((float) $r->horas, 2, ',', '.') }}</td>
                                <td class="py-2 text-right font-mono">{{ number_format($imp, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 text-slate-500">Sin líneas (factura vacía).</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($factura->pagos->isNotEmpty())
        <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60">
            <h3 class="font-semibold text-slate-900 mb-4">Pagos registrados</h3>
            <ul class="divide-y divide-slate-100 text-sm">
                @foreach ($factura->pagos as $pago)
                    <li class="py-3 flex justify-between gap-4">
                        <span class="text-slate-600">{{ $pago->fecha_pago->format('d/m/Y') }} · {{ $pago->metodo }} @if($pago->user) · {{ $pago->user->name }} @endif</span>
                        <span class="font-mono font-medium">{{ number_format((float) $pago->monto, 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</x-panel-layout>
