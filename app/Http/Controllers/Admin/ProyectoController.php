<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProyectoEstado;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Proyecto;
use App\Models\User;
use App\Services\PlanUsageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProyectoController extends Controller
{
    public function __construct(
        protected PlanUsageService $planUsage
    ) {}

    public function index(): View
    {
        $proyectos = Proyecto::query()
            ->with(['cliente', 'user'])
            ->withCount('tareas')
            ->orderByDesc('updated_at')
            ->paginate(12);

        return view('admin.proyectos.index', compact('proyectos'));
    }

    public function create(): View
    {
        $freelancers = User::query()->where('role', UserRole::Freelancer)->orderBy('name')->get();
        $clientes = Cliente::query()->with('user')->orderBy('nombre')->get();

        return view('admin.proyectos.create', compact('freelancers', 'clientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $owner = User::query()->findOrFail($data['user_id']);
        $org = $owner->organization;
        if ($org && ! $this->planUsage->canCreateProject($org)) {
            return back()->withInput()->with('error', 'Ese estudio alcanzó el límite de proyectos del plan gratuito.');
        }
        $data['organization_id'] = $org?->id;
        Proyecto::query()->create($data);

        return redirect()->route('admin.proyectos.index')->with('ok', 'Proyecto creado.');
    }

    public function show(Proyecto $proyecto): View
    {
        $proyecto->load(['cliente', 'user', 'tareas' => fn ($q) => $q->orderBy('orden')->orderBy('id')]);

        return view('admin.proyectos.show', compact('proyecto'));
    }

    public function edit(Proyecto $proyecto): View
    {
        $freelancers = User::query()->where('role', UserRole::Freelancer)->orderBy('name')->get();
        $clientes = Cliente::query()->with('user')->orderBy('nombre')->get();

        return view('admin.proyectos.edit', compact('proyecto', 'freelancers', 'clientes'));
    }

    public function update(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $proyecto->update($this->validated($request));

        return redirect()->route('admin.proyectos.show', $proyecto)->with('ok', 'Proyecto actualizado.');
    }

    public function destroy(Proyecto $proyecto): RedirectResponse
    {
        $proyecto->delete();

        return redirect()->route('admin.proyectos.index')->with('ok', 'Proyecto eliminado.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'cliente_id' => ['required', 'exists:clientes,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', Rule::enum(ProyectoEstado::class)],
            'tarifa_hora' => ['required', 'numeric', 'min:0'],
            'moneda' => ['required', 'string', 'max:8'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ]);
    }
}
