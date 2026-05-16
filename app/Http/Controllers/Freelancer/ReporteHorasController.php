<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\RegistroHoras;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReporteHorasController extends Controller
{
    public function __invoke(Request $request): View
    {
        $desde = $request->query('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->query('hasta', now()->endOfMonth()->toDateString());

        $uid = auth()->id();

        $registros = RegistroHoras::query()
            ->where('user_id', $uid)
            ->whereBetween('fecha', [$desde, $hasta])
            ->with(['tarea.proyecto.cliente'])
            ->orderBy('fecha')
            ->orderBy('id')
            ->get();

        $totalesPorProyecto = $registros
            ->groupBy(fn ($r) => $r->tarea->proyecto_id)
            ->map(fn ($group) => [
                'proyecto' => $group->first()->tarea->proyecto->nombre,
                'horas' => round($group->sum('horas'), 2),
            ])
            ->values();

        $totalHoras = round($registros->sum('horas'), 2);

        return view('freelancer.reporte', compact('registros', 'totalesPorProyecto', 'totalHoras', 'desde', 'hasta'));
    }
}
