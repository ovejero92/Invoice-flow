<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\RegistroHoras;
use App\Models\Tarea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegistroHoraController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tarea_id' => ['required', 'exists:tareas,id'],
            'fecha' => ['required', 'date'],
            'horas' => ['required', 'numeric', 'min:0.01', 'max:24'],
            'notas' => ['nullable', 'string', 'max:2000'],
        ]);

        $tarea = Tarea::query()->with('proyecto')->findOrFail($data['tarea_id']);
        abort_unless($tarea->proyecto->user_id === auth()->id(), 403);

        RegistroHoras::query()->create([
            'tarea_id' => $tarea->id,
            'user_id' => auth()->id(),
            'fecha' => $data['fecha'],
            'horas' => $data['horas'],
            'notas' => $data['notas'] ?? null,
        ]);

        return back()->with('ok', 'Horas registradas.');
    }
}
