<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Services\PlanUsageService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FacturaExportController extends Controller
{
    public function __construct(
        protected PlanUsageService $planUsage
    ) {}

    public function __invoke(Request $request): StreamedResponse
    {
        $user = $request->user();
        $organization = $user->organization;

        if (! $user->isAdmin() && (! $organization || ! $this->planUsage->canExportCsv($organization))) {
            abort(403, 'Exportación CSV disponible en plan Pro.');
        }

        $filename = 'facturas-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'numero', 'fecha_emision', 'cliente', 'periodo_desde', 'periodo_hasta',
                'subtotal', 'impuesto_pct', 'impuesto_importe', 'total', 'estado',
            ], ';');

            Factura::query()
                ->with('cliente')
                ->orderByDesc('fecha_emision')
                ->chunk(100, function ($facturas) use ($handle) {
                    foreach ($facturas as $f) {
                        fputcsv($handle, [
                            $f->numero,
                            $f->fecha_emision->format('Y-m-d'),
                            $f->cliente->nombre,
                            $f->periodo_desde->format('Y-m-d'),
                            $f->periodo_hasta->format('Y-m-d'),
                            $f->subtotal,
                            $f->impuesto_pct,
                            $f->impuesto_importe,
                            $f->total,
                            $f->estado->value,
                        ], ';');
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
