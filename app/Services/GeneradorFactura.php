<?php

namespace App\Services;

use App\Enums\FacturaEstado;
use App\Models\Factura;
use App\Models\Proyecto;
use App\Models\RegistroHoras;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class GeneradorFactura
{
    /**
     * Genera una factura emitida y asocia los registros de horas libres del periodo.
     *
     * @return array{factura: Factura, registros_asignados: int}
     */
    public function generar(
        Proyecto $proyecto,
        Carbon $desde,
        Carbon $hasta,
        float $impuestoPct,
        ?string $notas = null
    ): array {
        if ($desde->greaterThan($hasta)) {
            throw new InvalidArgumentException('La fecha desde debe ser anterior o igual a la fecha hasta.');
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
        $impuestoImporte = round($subtotal * ($impuestoPct / 100), 2);
        $total = round($subtotal + $impuestoImporte, 2);

        return DB::transaction(function () use ($proyecto, $desde, $hasta, $subtotal, $impuestoPct, $impuestoImporte, $total, $notas, $registros) {
            $numero = $this->siguienteNumero($proyecto->user_id);

            $factura = Factura::query()->create([
                'user_id' => $proyecto->user_id,
                'cliente_id' => $proyecto->cliente_id,
                'numero' => $numero,
                'fecha_emision' => now()->toDateString(),
                'periodo_desde' => $desde->toDateString(),
                'periodo_hasta' => $hasta->toDateString(),
                'subtotal' => $subtotal,
                'impuesto_pct' => $impuestoPct,
                'impuesto_importe' => $impuestoImporte,
                'total' => $total,
                'estado' => FacturaEstado::Emitida,
                'notas' => $notas,
            ]);

            RegistroHoras::query()->whereIn('id', $registros->pluck('id'))->update(['factura_id' => $factura->id]);

            return ['factura' => $factura->fresh(['cliente', 'registrosHoras.tarea']), 'registros_asignados' => $registros->count()];
        });
    }

    protected function siguienteNumero(int $userId): string
    {
        $year = now()->format('Y');
        $prefix = $year.'-';

        $last = Factura::query()
            ->where('user_id', $userId)
            ->where('numero', 'like', $prefix.'%')
            ->orderByDesc('numero')
            ->value('numero');

        $next = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
