<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use App\Models\Tarea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\TareaEstado;

class TareaController extends Controller
{
    public function store(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', Rule::enum(TareaEstado::class)],
            'orden' => ['nullable', 'integer', 'min:0'],
            'facturable' => ['boolean'],
        ]);

        $proyecto->tareas()->create([
            ...$data,
            'facturable' => $request->boolean('facturable', true),
        ]);

        return redirect()->route('admin.proyectos.show', $proyecto)->with('ok', 'Tarea creada.');
    }

    public function update(Request $request, Tarea $tarea): RedirectResponse
    {
        $proyecto = $tarea->proyecto;

        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', Rule::enum(TareaEstado::class)],
            'orden' => ['nullable', 'integer', 'min:0'],
            'facturable' => ['boolean'],
        ]);

        $tarea->update([
            ...$data,
            'facturable' => $request->boolean('facturable', true),
        ]);

        return redirect()->route('admin.proyectos.show', $proyecto)->with('ok', 'Tarea actualizada.');
    }

    public function destroy(Tarea $tarea): RedirectResponse
    {
        $proyecto = $tarea->proyecto;
        $tarea->delete();

        return redirect()->route('admin.proyectos.show', $proyecto)->with('ok', 'Tarea eliminada.');
    }
}
