<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FacturaEstado;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Proyecto;
use App\Models\RegistroHoras;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'clientes' => Cliente::query()->count(),
            'proyectosActivos' => Proyecto::query()->where('estado', 'activo')->count(),
            'facturasPendientes' => Factura::query()
                ->whereIn('estado', [FacturaEstado::Emitida, FacturaEstado::Borrador])
                ->count(),
            'horasMes' => RegistroHoras::query()
                ->whereMonth('fecha', now()->month)
                ->whereYear('fecha', now()->year)
                ->sum('horas'),
        ]);
    }
}
