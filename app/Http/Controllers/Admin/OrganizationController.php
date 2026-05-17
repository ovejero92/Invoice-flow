<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubscriptionPlan;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function index(): View
    {
        $organizations = Organization::query()
            ->withCount(['users', 'clientes', 'proyectos', 'facturas'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.organizations.index', compact('organizations'));
    }

    public function upgrade(Organization $organization): RedirectResponse
    {
        $organization->update([
            'plan' => SubscriptionPlan::Pro,
            'plan_expires_at' => null,
        ]);

        return back()->with('ok', "Estudio «{$organization->name}» pasó a plan Pro (activación manual).");
    }

    public function downgrade(Organization $organization): RedirectResponse
    {
        $organization->update([
            'plan' => SubscriptionPlan::Free,
            'plan_expires_at' => null,
        ]);

        return back()->with('ok', "Estudio «{$organization->name}» volvió a plan Gratis.");
    }
}
