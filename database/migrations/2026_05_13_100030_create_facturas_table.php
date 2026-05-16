<?php

use App\Enums\FacturaEstado;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('numero');
            $table->date('fecha_emision');
            $table->date('periodo_desde');
            $table->date('periodo_hasta');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('impuesto_pct', 5, 2)->default(0);
            $table->decimal('impuesto_importe', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('estado')->default(FacturaEstado::Borrador->value);
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
