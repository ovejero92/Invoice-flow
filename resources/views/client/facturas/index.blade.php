@php
    use App\Enums\FacturaEstado;
    $estadoEtiqueta = fn (FacturaEstado $e) => match ($e) {
        FacturaEstado::Borrador => 'Borrador',
        FacturaEstado::Emitida => 'Pendiente de pago',
        FacturaEstado::Pagada => 'Pagada',
        FacturaEstado::Anulada => 'Anulada',
    };
@endphp

<x-panel-layout title="Facturas" subtitle="PDF y pagos">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-3">Número</th>
                    <th class="px-6 py-3">Emisión</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3 text-right">Total</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($facturas as $f)
                    <tr class="hover:bg-amber-50/30">
                        <td class="px-6 py-4 font-mono font-medium">{{ $f->numero }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $f->fecha_emision->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $estadoEtiqueta($f->estado) }}</td>
                        <td class="px-6 py-4 text-right font-mono">{{ number_format((float) $f->total, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('client.facturas.show', $f) }}" class="text-amber-900 font-semibold hover:underline">Ver</a>
                            <a href="{{ route('facturas.pdf', $f) }}" class="text-slate-600 hover:underline">PDF</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">No hay facturas todavía.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $facturas->links() }}</div>
</x-panel-layout>
