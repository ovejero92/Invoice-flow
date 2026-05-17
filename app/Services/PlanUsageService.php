<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Organization;
use Illuminate\Support\Carbon;

class PlanUsageService
{
    public function usage(Organization $organization): array
    {
        $monthStart = Carbon::now()->startOfMonth();

        return [
            'clients' => $organization->clientes()->count(),
            'projects' => $organization->proyectos()->count(),
            'invoices_this_month' => $organization->facturas()
                ->where('created_at', '>=', $monthStart)
                ->count(),
            'client_portal_users' => $organization->users()
                ->where('role', 'client')
                ->count(),
        ];
    }

    public function canCreateClient(Organization $organization): bool
    {
        return $this->usage($organization)['clients'] < (int) $organization->limit('max_clients');
    }

    public function canCreateProject(Organization $organization): bool
    {
        return $this->usage($organization)['projects'] < (int) $organization->limit('max_projects');
    }

    public function canCreateInvoice(Organization $organization): bool
    {
        return $this->usage($organization)['invoices_this_month'] < (int) $organization->limit('max_invoices_per_month');
    }

    public function canCreateClientPortalUser(Organization $organization): bool
    {
        return $this->usage($organization)['client_portal_users'] < (int) $organization->limit('max_client_portal_users');
    }

    public function canExportCsv(Organization $organization): bool
    {
        return (bool) $organization->limit('csv_export');
    }
}
