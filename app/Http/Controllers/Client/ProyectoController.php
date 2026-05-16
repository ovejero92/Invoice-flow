<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\View\View;

class ProyectoController extends Controller
{
    protected function clienteId(): int
    {
        $cid = auth()->user()->cliente_id;
        abort_unless($cid, 403);

        return $cid;
    }

    public function index(): View
    {
        $proyectos = Proyecto::query()
            ->where('cliente_id', $this->clienteId())
            ->withCount('tareas')
            ->orderByRaw("CASE estado WHEN 'activo' THEN 0 WHEN 'pausado' THEN 1 WHEN 'completado' THEN 2 ELSE 3 END")
            ->orderBy('nombre')
            ->paginate(12);

        return view('client.proyectos.index', compact('proyectos'));
    }

    public function show(Proyecto $proyecto): View
    {
        abort_unless($proyecto->cliente_id === $this->clienteId(), 403);

        $proyecto->load(['tareas' => fn ($q) => $q->orderBy('orden')->orderBy('id')]);

        return view('client.proyectos.show', compact('proyecto'));
    }
}
