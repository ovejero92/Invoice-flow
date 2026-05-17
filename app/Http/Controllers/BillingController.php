<?php

namespace App\Http\Controllers;

use App\Services\PlanUsageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct(
        protected PlanUsageService $planUsage
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user->isAdmin() && ! $user->organization_id) {
            return redirect()->route('admin.dashboard');
        }

        $organization = $user->organization;
        if (! $organization) {
            abort(403);
        }

        $usage = $this->planUsage->usage($organization);
        $limits = config('plans.limits.'.$organization->planKey());
        $pricing = config('plans.pricing');

        return view('billing.index', compact('organization', 'usage', 'limits', 'pricing'));
    }
}
