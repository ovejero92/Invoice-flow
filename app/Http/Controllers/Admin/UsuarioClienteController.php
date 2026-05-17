<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use App\Services\PlanUsageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UsuarioClienteController extends Controller
{
    public function __construct(
        protected PlanUsageService $planUsage
    ) {}

    public function create(): View
    {
        $clientes = Cliente::query()->with('user')->orderBy('nombre')->get();

        return view('admin.usuarios-cliente.create', compact('clientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $cliente = Cliente::query()->findOrFail($data['cliente_id']);
        $org = $cliente->organization;
        if ($org && ! $this->planUsage->canCreateClientPortalUser($org)) {
            return back()->withInput()->with('error', 'Límite de usuarios de portal cliente del plan gratuito alcanzado.');
        }

        User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::Client,
            'cliente_id' => $data['cliente_id'],
            'organization_id' => $org?->id,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.clientes.index')->with('ok', 'Usuario de cliente creado. Ya puede iniciar sesión.');
    }
}
