<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use Illuminate\View\View;

class FacturaController extends Controller
{
    protected function clienteId(): int
    {
        $cid = auth()->user()->cliente_id;
        abort_unless($cid, 403);

        return $cid;
    }

    public function index(): View
    {
        $facturas = Factura::query()
            ->where('cliente_id', $this->clienteId())
            ->orderByDesc('fecha_emision')
            ->paginate(15);

        return view('client.facturas.index', compact('facturas'));
    }

    public function show(Factura $factura): View
    {
        abort_unless($factura->cliente_id === $this->clienteId(), 403);

        $factura->load(['registrosHoras.tarea', 'pagos']);

        return view('client.facturas.show', compact('factura'));
    }
}
