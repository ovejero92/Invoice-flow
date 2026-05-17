<?php

namespace App\Models;

use App\Enums\SubscriptionPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'plan',
        'plan_expires_at',
        'legal_name',
        'tax_id',
        'legal_address',
        'invoice_series_prefix',
        'default_tax_rate',
        'country_code',
    ];

    protected function casts(): array
    {
        return [
            'plan' => SubscriptionPlan::class,
            'plan_expires_at' => 'datetime',
            'default_tax_rate' => 'integer',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }

    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }

    public function isPro(): bool
    {
        if ($this->plan === SubscriptionPlan::Pro) {
            if ($this->plan_expires_at === null) {
                return true;
            }

            return $this->plan_expires_at->isFuture();
        }

        return false;
    }

    public function planKey(): string
    {
        return $this->isPro() ? 'pro' : 'free';
    }

    public function limit(string $key): mixed
    {
        return config('plans.limits.'.$this->planKey().'.'.$key);
    }
}
