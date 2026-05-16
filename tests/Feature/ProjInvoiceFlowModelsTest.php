<?php

use App\Enums\FacturaEstado;
use App\Enums\ProyectoEstado;
use App\Enums\TareaEstado;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Proyecto;
use App\Models\RegistroHoras;
use App\Models\Tarea;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('cliente proyecto tarea registro y factura enlazan correctamente en eloquent', function () {
    $user = User::factory()->create();

    $cliente = Cliente::create([
        'user_id' => $user->id,
        'nombre' => 'ACME SL',
    ]);

    $proyecto = Proyecto::create([
        'user_id' => $user->id,
        'cliente_id' => $cliente->id,
        'nombre' => 'Sitio corporativo',
        'estado' => ProyectoEstado::Activo,
    ]);

    $tarea = Tarea::create([
        'proyecto_id' => $proyecto->id,
        'titulo' => 'Maquetación',
        'estado' => TareaEstado::EnProgreso,
    ]);

    $registro = RegistroHoras::create([
        'tarea_id' => $tarea->id,
        'user_id' => $user->id,
        'fecha' => now()->toDateString(),
        'horas' => 2.5,
    ]);

    $factura = Factura::create([
        'user_id' => $user->id,
        'cliente_id' => $cliente->id,
        'numero' => 'F-2026-001',
        'fecha_emision' => now()->toDateString(),
        'periodo_desde' => now()->subWeek()->toDateString(),
        'periodo_hasta' => now()->toDateString(),
        'subtotal' => 100,
        'total' => 100,
        'estado' => FacturaEstado::Borrador,
    ]);

    $registro->update(['factura_id' => $factura->id]);

    expect($cliente->user->is($user))->toBeTrue()
        ->and($proyecto->cliente->is($cliente))->toBeTrue()
        ->and($tarea->proyecto->is($proyecto))->toBeTrue()
        ->and($registro->fresh()->factura->is($factura))->toBeTrue()
        ->and($factura->registrosHoras)->toHaveCount(1);
});
