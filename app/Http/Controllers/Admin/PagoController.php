<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacturaPago;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function __invoke(): View
    {
        $pagos = FacturaPago::query()
            ->with(['factura.cliente', 'user'])
            ->orderByDesc('fecha_pago')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.pagos.index', compact('pagos'));
    }
}
