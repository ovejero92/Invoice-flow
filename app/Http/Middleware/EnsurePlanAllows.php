<?php

namespace App\Http\Middleware;

use App\Services\PlanUsageService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlanAllows
{
    public function __construct(
        protected PlanUsageService $usage
    ) {}

    public function handle(Request $request, Closure $next, string $action): Response
    {
        $user = $request->user();
        if (! $user || $user->isAdmin()) {
            return $next($request);
        }

        $organization = $user->organization;
        if (! $organization) {
            abort(403, 'Tu cuenta no tiene un estudio asociado.');
        }

        $allowed = match ($action) {
            'client' => $this->usage->canCreateClient($organization),
            'project' => $this->usage->canCreateProject($organization),
            'invoice' => $this->usage->canCreateInvoice($organization),
            'client_user' => $this->usage->canCreateClientPortalUser($organization),
            default => true,
        };

        if (! $allowed) {
            return redirect()
                ->route('billing.index')
                ->with('error', 'Alcanzaste el límite de tu plan gratuito. Pasá a Pro para seguir creciendo.');
        }

        return $next($request);
    }
}
