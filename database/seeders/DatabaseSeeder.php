<?php

namespace Database\Seeders;

use App\Enums\ProyectoEstado;
use App\Enums\TareaEstado;
use App\Enums\UserRole;
use App\Models\Cliente;
use App\Models\Proyecto;
use App\Models\RegistroHoras;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * En producción (APP_ENV=production) este seeder no crea datos demo.
     * Creá el primer admin con: php artisan invoiceflow:create-admin tu@email.com
     * Forzar demo en otro entorno: DEMO_SEED=true en .env
     */
    public function run(): void
    {
        $forceDemo = filter_var(env('DEMO_SEED', false), FILTER_VALIDATE_BOOL);
        if (app()->environment('production') && ! $forceDemo) {
            return;
        }

        User::query()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@invoiceflow.demo',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
            'cliente_id' => null,
        ]);

        $freelancer = User::query()->create([
            'name' => 'Gustavo Freelancer',
            'email' => 'freelancer@invoiceflow.demo',
            'password' => Hash::make('password'),
            'role' => UserRole::Freelancer,
            'cliente_id' => null,
        ]);

        $cliente = Cliente::query()->create([
            'user_id' => $freelancer->id,
            'nombre' => 'Estudio Vértigo SL',
            'email' => 'contacto@vertigo.demo',
            'telefono' => '+34 900 000 000',
            'direccion' => 'Calle Demo 123, Madrid',
            'notas' => 'Cliente semilla para el panel.',
        ]);

        User::query()->create([
            'name' => 'María Cliente',
            'email' => 'cliente@invoiceflow.demo',
            'password' => Hash::make('password'),
            'role' => UserRole::Client,
            'cliente_id' => $cliente->id,
        ]);

        $proyecto = Proyecto::query()->create([
            'user_id' => $freelancer->id,
            'cliente_id' => $cliente->id,
            'nombre' => 'Rediseño marca corporativa',
            'descripcion' => 'Proyecto demo con tareas y horas listas para facturar.',
            'estado' => ProyectoEstado::Activo,
            'tarifa_hora' => 85,
            'moneda' => 'EUR',
            'fecha_inicio' => now()->subMonths(2)->toDateString(),
            'fecha_fin' => now()->addMonth()->toDateString(),
        ]);

        $t1 = Tarea::query()->create([
            'proyecto_id' => $proyecto->id,
            'titulo' => 'Brand workshop y auditoría',
            'descripcion' => 'Kickoff y discovery.',
            'estado' => TareaEstado::Completada,
            'orden' => 1,
            'facturable' => true,
        ]);

        $t2 = Tarea::query()->create([
            'proyecto_id' => $proyecto->id,
            'titulo' => 'UI kit en Figma',
            'estado' => TareaEstado::EnProgreso,
            'orden' => 2,
            'facturable' => true,
        ]);

        RegistroHoras::query()->create([
            'tarea_id' => $t1->id,
            'user_id' => $freelancer->id,
            'fecha' => now()->subDays(10)->toDateString(),
            'horas' => 6,
            'notas' => 'Sesiones con cliente',
        ]);

        RegistroHoras::query()->create([
            'tarea_id' => $t1->id,
            'user_id' => $freelancer->id,
            'fecha' => now()->subDays(5)->toDateString(),
            'horas' => 3.5,
        ]);

        RegistroHoras::query()->create([
            'tarea_id' => $t2->id,
            'user_id' => $freelancer->id,
            'fecha' => now()->subDays(2)->toDateString(),
            'horas' => 4,
        ]);
    }
}
