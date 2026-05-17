<?php

namespace App\Services;

use App\Enums\FacturaEstado;
use App\Models\Factura;
use App\Models\Organization;
use App\Models\Proyecto;
use App\Models\RegistroHoras;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class GeneradorFactura
{
    public function __construct(
        protected PlanUsageService $planUsage
    ) {}

    /**
     * @return array{factura: Factura, registros_asignados: int}
     */
    public function generar(
        Proyecto $proyecto,
        Carbon $desde,
        Carbon $hasta,
        ?float $impuestoPct = null,
        ?string $notas = null
    ): array {
        if ($desde->greaterThan($hasta)) {
            throw new InvalidArgumentException('La fecha desde debe ser anterior o igual a la fecha hasta.');
        }

        $organization = $proyecto->organization ?? $proyecto->user?->organization;
        if ($organization && ! $this->planUsage->canCreateInvoice($organization)) {
            throw new InvalidArgumentException('Límite de facturas del plan gratuito alcanzado este mes. Pasá a Pro para continuar.');
        }

        $ids = RegistroHoras::query()
            ->whereHas('tarea', fn ($q) => $q->where('proyecto_id', $proyecto->id)->where('facturable', true))
            ->whereBetween('fecha', [$desde->toDateString(), $hasta->toDateString()])
            ->whereNull('factura_id')
            ->pluck('id');

        if ($ids->isEmpty()) {
            throw new InvalidArgumentException('No hay horas facturables sin facturar en el rango seleccionado.');
        }

        $registros = RegistroHoras::query()->whereIn('id', $ids)->lockForUpdate()->get();
        $tarifa = (float) $proyecto->tarifa_hora;
        $subtotal = round(
            $registros->sum(fn (RegistroHoras $r) => (float) $r->horas * $tarifa),
            2
        );

        $taxPct = $impuestoPct ?? (float) ($organization?->default_tax_rate ?? 21);
        $impuestoImporte = round($subtotal * ($taxPct / 100), 2);
        $total = round($subtotal + $impuestoImporte, 2);

        return DB::transaction(function () use ($proyecto, $organization, $desde, $hasta, $subtotal, $taxPct, $impuestoImporte, $total, $notas, $registros) {
            $numero = $this->siguienteNumero($organization, $proyecto->user_id);

            $factura = Factura::query()->create([
                'organization_id' => $organization?->id ?? $proyecto->organization_id,
                'user_id' => $proyecto->user_id,
                'cliente_id' => $proyecto->cliente_id,
                'numero' => $numero,
                'fecha_emision' => now()->toDateString(),
                'periodo_desde' => $desde->toDateString(),
                'periodo_hasta' => $hasta->toDateString(),
                'subtotal' => $subtotal,
                'impuesto_pct' => $taxPct,
                'impuesto_importe' => $impuestoImporte,
                'total' => $total,
                'estado' => FacturaEstado::Emitida,
                'notas' => $notas,
            ]);

            RegistroHoras::query()->whereIn('id', $registros->pluck('id'))->update(['factura_id' => $factura->id]);

            return ['factura' => $factura->fresh(['cliente', 'registrosHoras.tarea', 'organization']), 'registros_asignados' => $registros->count()];
        });
    }

    protected function siguienteNumero(?Organization $organization, int $userId): string
    {
        $year = now()->format('Y');
        $series = $organization?->invoice_series_prefix ? trim($organization->invoice_series_prefix).'-'.$year.'-' : $year.'-';

        $query = Factura::query()->where('numero', 'like', $series.'%');

        if ($organization) {
            $query->where('organization_id', $organization->id);
        } else {
            $query->where('user_id', $userId);
        }

        $last = $query->orderByDesc('numero')->value('numero');
        $next = $last ? ((int) substr($last, strlen($series))) + 1 : 1;

        return $series.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
