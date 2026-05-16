<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaPdfController extends Controller
{
    public function __invoke(Factura $factura)
    {
        $this->authorizeFactura($factura);

        $factura->load(['cliente', 'user', 'registrosHoras.tarea.proyecto']);

        return Pdf::loadView('pdf.factura', ['factura' => $factura])
            ->download('factura-'.$factura->numero.'.pdf');
    }

    protected function authorizeFactura(Factura $factura): void
    {
        $u = auth()->user();
        if ($u->isAdmin()) {
            return;
        }
        if ($u->isFreelancer() && $factura->user_id === $u->id) {
            return;
        }
        if ($u->isClient() && $u->cliente_id && $factura->cliente_id === $u->cliente_id) {
            return;
        }
        abort(403);
    }
}
