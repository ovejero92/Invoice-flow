<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        foreach (['clientes', 'proyectos', 'facturas'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('organization_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        $this->backfillOrganizations();
    }

    public function down(): void
    {
        foreach (['facturas', 'proyectos', 'clientes', 'users'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('organization_id');
            });
        }
    }

    protected function backfillOrganizations(): void
    {
        $freelancerIds = DB::table('users')
            ->where('role', 'freelancer')
            ->pluck('id');

        foreach ($freelancerIds as $userId) {
            $user = DB::table('users')->where('id', $userId)->first();
            $orgName = ($user->name ?? 'Estudio').' — InvoiceFlow';
            $slug = Str::slug($orgName).'-'.$userId;

            $orgId = DB::table('organizations')->insertGetId([
                'name' => $orgName,
                'slug' => $slug,
                'plan' => 'free',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->where('id', $userId)->update(['organization_id' => $orgId]);

            foreach (['clientes', 'proyectos', 'facturas'] as $table) {
                DB::table($table)->where('user_id', $userId)->update(['organization_id' => $orgId]);
            }
        }
    }
};
