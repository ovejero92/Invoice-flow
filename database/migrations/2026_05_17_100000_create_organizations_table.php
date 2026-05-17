<?php

use App\Enums\SubscriptionPlan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('plan')->default(SubscriptionPlan::Free->value);
            $table->timestamp('plan_expires_at')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('legal_address')->nullable();
            $table->string('invoice_series_prefix', 16)->default('');
            $table->unsignedTinyInteger('default_tax_rate')->default(21);
            $table->string('country_code', 2)->default('ES');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
