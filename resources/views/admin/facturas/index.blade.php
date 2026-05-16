@php
    use App\Enums\FacturaEstado;
    $estadoEtiqueta = fn (FacturaEstado $e) => match ($e) {
        FacturaEstado::Borrador => 'Borrador',
        FacturaEstado::Emitida => 'Emitida',
        FacturaEstado::Pagada => 'Pagada',
        FacturaEstado::Anulada => 'Anulada',
    };
@endphp

<x-panel-layout title="Facturación" subtitle="Historial y generación por periodo">
    <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mb-6">
        <p class="text-sm text-slate-600">Las facturas toman horas registradas, facturables y sin asignar a otra factura.</p>
        <a href="{{ route('admin.facturas.create') }}" class="inline-flex justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">Generar factura</a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3">Número</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Emisión</th>
                    <th class="px-6 py-3">Periodo</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3 text-right">Total</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($facturas as $f)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4 font-mono font-medium text-slate-900">{{ $f->numero }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $f->cliente->nombre }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $f->fecha_emision->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-slate-600 text-xs">{{ $f->periodo_desde->format('d/m/Y') }} — {{ $f->periodo_hasta->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">{{ $estadoEtiqueta($f->estado) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono text-slate-900">{{ number_format((float) $f->total, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.facturas.show', $f) }}" class="text-indigo-600 hover:underline">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-500">No hay facturas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $facturas->links() }}</div>
</x-panel-layout>
