<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\View\View;

class ProyectoController extends Controller
{
    public function index(): View
    {
        $proyectos = Proyecto::query()
            ->where('user_id', auth()->id())
            ->with('cliente')
            ->withCount('tareas')
            ->orderByRaw("CASE estado WHEN 'activo' THEN 0 WHEN 'pausado' THEN 1 ELSE 2 END")
            ->orderBy('nombre')
            ->paginate(12);

        return view('freelancer.proyectos.index', compact('proyectos'));
    }

    public function show(Proyecto $proyecto): View
    {
        abort_unless($proyecto->user_id === auth()->id(), 403);

        $proyecto->load(['cliente', 'tareas' => fn ($q) => $q->orderBy('orden')->orderBy('id')]);

        return view('freelancer.proyectos.show', compact('proyecto'));
    }
}
