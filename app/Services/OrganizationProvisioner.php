<?php

namespace App\Services;

use App\Enums\SubscriptionPlan;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Str;

class OrganizationProvisioner
{
    public function createForFreelancer(User $user): Organization
    {
        $base = Str::slug($user->name ?: 'estudio');
        $slug = $base.'-'.Str::lower(Str::random(6));

        $organization = Organization::query()->create([
            'name' => trim($user->name).' — Estudio',
            'slug' => $slug,
            'plan' => SubscriptionPlan::Free,
        ]);

        $user->update(['organization_id' => $organization->id]);

        return $organization;
    }
}
