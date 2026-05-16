<?php

namespace App\Http\Controllers\Freelancer;

use App\Enums\ProyectoEstado;
use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use App\Models\RegistroHoras;
use App\Models\Tarea;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $uid = auth()->id();

        $proyectosActivos = Proyecto::query()
            ->where('user_id', $uid)
            ->whereIn('estado', [ProyectoEstado::Activo, ProyectoEstado::Pausado])
            ->count();

        $tareasTimer = Tarea::query()
            ->whereHas('proyecto', fn ($q) => $q->where('user_id', $uid)->whereIn('estado', [ProyectoEstado::Activo, ProyectoEstado::Pausado]))
            ->with('proyecto:id,nombre')
            ->orderBy('titulo')
            ->get();

        $ultimosRegistros = RegistroHoras::query()
            ->where('user_id', $uid)
            ->with('tarea.proyecto')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $horasSemana = RegistroHoras::query()
            ->where('user_id', $uid)
            ->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('horas');

        return view('freelancer.dashboard', compact(
            'proyectosActivos',
            'tareasTimer',
            'ultimosRegistros',
            'horasSemana'
        ));
    }
}
