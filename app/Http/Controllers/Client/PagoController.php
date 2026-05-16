<?php

namespace App\Http\Controllers\Client;

use App\Enums\FacturaEstado;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\FacturaPago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function create(Factura $factura)
    {
        abort_unless($factura->cliente_id === auth()->user()->cliente_id, 403);
        abort_if($factura->estado === FacturaEstado::Anulada, 404);

        $factura->load('pagos');

        return view('client.pagos.create', compact('factura'));
    }

    public function store(Request $request, Factura $factura): RedirectResponse
    {
        abort_unless($factura->cliente_id === auth()->user()->cliente_id, 403);
        abort_if($factura->estado === FacturaEstado::Anulada, 404);

        $data = $request->validate([
            'monto' => ['required', 'numeric', 'min:0.01'],
            'fecha_pago' => ['required', 'date'],
            'metodo' => ['required', 'string', 'max:64'],
            'notas' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($factura, $data) {
            FacturaPago::query()->create([
                'factura_id' => $factura->id,
                'user_id' => auth()->id(),
                'fecha_pago' => $data['fecha_pago'],
                'monto' => $data['monto'],
                'metodo' => $data['metodo'],
                'notas' => $data['notas'] ?? null,
            ]);

            $factura->refresh();
            if ($factura->montoPagado() + 0.005 >= (float) $factura->total) {
                $factura->update(['estado' => FacturaEstado::Pagada]);
            }
        });

        return redirect()->route('client.facturas.show', $factura)->with('ok', 'Pago registrado.');
    }
}
