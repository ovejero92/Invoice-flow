<?php

namespace Tests\Feature;

use App\Enums\SubscriptionPlan;
use App\Enums\UserRole;
use App\Models\Organization;
use App\Models\User;
use App\Services\OrganizationProvisioner;
use App\Services\PlanUsageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanLimitsTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_plan_blocks_fourth_client(): void
    {
        $org = $this->makeOrganization(SubscriptionPlan::Free);
        $service = app(PlanUsageService::class);

        for ($i = 0; $i < 3; $i++) {
            $org->clientes()->create([
                'organization_id' => $org->id,
                'user_id' => $org->users()->first()->id,
                'nombre' => "Cliente {$i}",
                'email' => "c{$i}@test.com",
            ]);
        }

        $this->assertFalse($service->canCreateClient($org));
    }

    public function test_pro_plan_allows_more_clients(): void
    {
        $org = $this->makeOrganization(SubscriptionPlan::Pro);
        $service = app(PlanUsageService::class);

        for ($i = 0; $i < 10; $i++) {
            $org->clientes()->create([
                'organization_id' => $org->id,
                'user_id' => $org->users()->first()->id,
                'nombre' => "Cliente {$i}",
                'email' => "c{$i}@test.com",
            ]);
        }

        $this->assertTrue($service->canCreateClient($org));
    }

    public function test_csv_export_only_on_pro(): void
    {
        $free = $this->makeOrganization(SubscriptionPlan::Free);
        $pro = $this->makeOrganization(SubscriptionPlan::Pro);
        $service = app(PlanUsageService::class);

        $this->assertFalse($service->canExportCsv($free));
        $this->assertTrue($service->canExportCsv($pro));
    }

    protected function makeOrganization(SubscriptionPlan $plan): Organization
    {
        $user = User::factory()->create(['role' => UserRole::Freelancer]);
        app(OrganizationProvisioner::class)->createForFreelancer($user);
        $user->refresh();
        $org = $user->organization;
        $org->update(['plan' => $plan]);

        return $org->fresh();
    }
}
