<?php

namespace App\Http\Controllers\Client;

use App\Enums\ProyectoEstado;
use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $cid = auth()->user()->cliente_id;
        abort_unless($cid, 403);

        $proyectosEnCurso = Proyecto::query()
            ->where('cliente_id', $cid)
            ->whereIn('estado', [ProyectoEstado::Activo, ProyectoEstado::Pausado])
            ->count();

        $proyectosCompletados = Proyecto::query()
            ->where('cliente_id', $cid)
            ->where('estado', ProyectoEstado::Completado)
            ->count();

        return view('client.dashboard', compact('proyectosEnCurso', 'proyectosCompletados'));
    }
}
