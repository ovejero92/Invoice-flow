<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class InvoiceflowCreateAdmin extends Command
{
    protected $signature = 'invoiceflow:create-admin {email : Correo del administrador} {--name= : Nombre (solo usuarios nuevos)}';

    protected $description = 'Crea un administrador o asigna el rol admin a un usuario existente (uso en servidor; no expone registro público)';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email inválido.');

            return self::FAILURE;
        }

        $user = User::query()->where('email', $email)->first();

        if ($user) {
            $user->update([
                'role' => UserRole::Admin,
                'cliente_id' => null,
            ]);

            $this->info("El usuario {$email} ahora es administrador.");

            if ($this->confirm('¿Querés definir una nueva contraseña ahora?', false)) {
                $plain = (string) $this->secret('Nueva contraseña');
                $validator = validator(['password' => $plain], ['password' => ['required', Password::defaults()]]);
                if ($validator->fails()) {
                    $this->error((string) $validator->errors()->first('password'));

                    return self::FAILURE;
                }
                $user->update(['password' => Hash::make($plain)]);
                $this->info('Contraseña actualizada.');
            }

            return self::SUCCESS;
        }

        $plain = (string) $this->secret('Contraseña del nuevo administrador');
        $validator = validator(['password' => $plain], ['password' => ['required', Password::defaults()]]);
        if ($validator->fails()) {
            $this->error((string) $validator->errors()->first('password'));

            return self::FAILURE;
        }

        $name = $this->option('name') ?: $this->ask('Nombre completo');
        if (! is_string($name) || trim($name) === '') {
            $this->error('El nombre es obligatorio.');

            return self::FAILURE;
        }

        User::query()->create([
            'name' => trim($name),
            'email' => $email,
            'password' => Hash::make($plain),
            'role' => UserRole::Admin,
            'cliente_id' => null,
        ]);

        $this->info("Administrador creado: {$email}");

        return self::SUCCESS;
    }
}
