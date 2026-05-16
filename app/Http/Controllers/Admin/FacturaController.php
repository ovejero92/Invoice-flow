<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Proyecto;
use App\Services\GeneradorFactura;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacturaController extends Controller
{
    public function index(): View
    {
        $facturas = Factura::query()
            ->with(['cliente', 'user'])
            ->orderByDesc('fecha_emision')
            ->paginate(15);

        return view('admin.facturas.index', compact('facturas'));
    }

    public function show(Factura $factura): View
    {
        $factura->load(['cliente', 'user', 'registrosHoras.tarea', 'pagos.user']);

        return view('admin.facturas.show', compact('factura'));
    }

    public function create(): View
    {
        $proyectos = Proyecto::query()->with(['cliente', 'user'])->orderBy('nombre')->get();
        $proyectoPre = old('proyecto_id', request('proyecto_id'));

        return view('admin.facturas.create', compact('proyectos', 'proyectoPre'));
    }

    public function store(Request $request, GeneradorFactura $generador): RedirectResponse
    {
        $data = $request->validate([
            'proyecto_id' => ['required', 'exists:proyectos,id'],
            'periodo_desde' => ['required', 'date'],
            'periodo_hasta' => ['required', 'date', 'after_or_equal:periodo_desde'],
            'impuesto_pct' => ['required', 'numeric', 'min:0', 'max:100'],
            'notas' => ['nullable', 'string'],
        ]);

        $proyecto = Proyecto::query()->findOrFail($data['proyecto_id']);

        try {
            $result = $generador->generar(
                $proyecto,
                \Illuminate\Support\Carbon::parse($data['periodo_desde']),
                \Illuminate\Support\Carbon::parse($data['periodo_hasta']),
                (float) $data['impuesto_pct'],
                $data['notas'] ?? null
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.facturas.show', $result['factura'])
            ->with('ok', 'Factura generada. Se asignaron '.$result['registros_asignados'].' registros de horas.');
    }
}
