<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::query()
            ->with('user')
            ->withCount(['proyectos', 'facturas'])
            ->orderBy('nombre')
            ->paginate(12);

        return view('admin.clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        $freelancers = User::query()->where('role', UserRole::Freelancer)->orderBy('name')->get();

        return view('admin.clientes.create', compact('freelancers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string'],
            'notas' => ['nullable', 'string'],
        ]);

        Cliente::query()->create($data);

        return redirect()->route('admin.clientes.index')->with('ok', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente): View
    {
        $freelancers = User::query()->where('role', UserRole::Freelancer)->orderBy('name')->get();

        return view('admin.clientes.edit', compact('cliente', 'freelancers'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string'],
            'notas' => ['nullable', 'string'],
        ]);

        $cliente->update($data);

        return redirect()->route('admin.clientes.index')->with('ok', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('admin.clientes.index')->with('ok', 'Cliente eliminado.');
    }
}
