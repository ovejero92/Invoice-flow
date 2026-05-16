@php
    use App\Enums\FacturaEstado;
    $estadoEtiqueta = fn (FacturaEstado $e) => match ($e) {
        FacturaEstado::Borrador => 'Borrador',
        FacturaEstado::Emitida => 'Pendiente de pago',
        FacturaEstado::Pagada => 'Pagada',
        FacturaEstado::Anulada => 'Anulada',
    };
@endphp

<x-panel-layout title="Factura {{ $factura->numero }}" subtitle="{{ $estadoEtiqueta($factura->estado) }}">
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('facturas.pdf', $factura) }}" class="rounded-xl bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-amber-500">Descargar PDF</a>
        @if ($factura->estado !== FacturaEstado::Pagada && $factura->estado !== FacturaEstado::Anulada && $factura->saldoPendiente() > 0)
            <a href="{{ route('client.pagos.create', $factura) }}" class="rounded-xl border border-amber-300 px-4 py-2 text-sm font-semibold text-amber-900 hover:bg-amber-50">Registrar pago</a>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-2 max-w-4xl">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60">
            <h3 class="text-xs font-semibold text-slate-500 uppercase">Resumen</h3>
            <dl class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Total</dt><dd class="font-mono font-semibold">{{ number_format((float) $factura->total, 2, ',', '.') }}</dd></div>
                <div class="flex justify-between text-emerald-700"><dt>Pagado</dt><dd class="font-mono">{{ number_format($factura->montoPagado(), 2, ',', '.') }}</dd></div>
                <div class="flex justify-between text-amber-900"><dt>Pendiente</dt><dd class="font-mono">{{ number_format($factura->saldoPendiente(), 2, ',', '.') }}</dd></div>
                <div class="flex justify-between text-slate-600 text-xs pt-2 border-t border-slate-100"><dt>Periodo</dt><dd>{{ $factura->periodo_desde->format('d/m/Y') }} — {{ $factura->periodo_hasta->format('d/m/Y') }}</dd></div>
            </dl>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60">
            <h3 class="text-xs font-semibold text-slate-500 uppercase">Conceptos</h3>
            <ul class="mt-4 space-y-2 text-sm text-slate-700">
                @foreach ($factura->registrosHoras as $r)
                    <li class="flex justify-between gap-2">
                        <span>{{ $r->fecha->format('d/m') }} · {{ $r->tarea->titulo }}</span>
                        <span class="font-mono shrink-0">{{ number_format((float) $r->horas, 2, ',', '.') }} h</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    @if ($factura->pagos->isNotEmpty())
        <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 max-w-4xl">
            <h3 class="font-semibold text-slate-900 mb-3">Pagos que registraste</h3>
            <ul class="text-sm divide-y divide-slate-100">
                @foreach ($factura->pagos as $p)
                    <li class="py-2 flex justify-between">
                        <span class="text-slate-600">{{ $p->fecha_pago->format('d/m/Y') }} · {{ $p->metodo }}</span>
                        <span class="font-mono">{{ number_format((float) $p->monto, 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</x-panel-layout>
