<x-panel-layout title="Pagos recibidos" subtitle="Histórico de cobros sobre facturas">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Factura</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Registró</th>
                    <th class="px-6 py-3">Método</th>
                    <th class="px-6 py-3 text-right">Monto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pagos as $p)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4 text-slate-600">{{ $p->fecha_pago->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 font-mono">{{ $p->factura->numero }}</td>
                        <td class="px-6 py-4">{{ $p->factura->cliente->nombre }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $p->user?->name ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $p->metodo }}</td>
                        <td class="px-6 py-4 text-right font-mono">{{ number_format((float) $p->monto, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">Aún no hay pagos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $pagos->links() }}</div>
</x-panel-layout>
