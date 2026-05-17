<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class InvoiceflowCreateAdmin extends Command
{
    protected $signature = 'invoiceflow:create-admin
                            {email : Correo del administrador}
                            {--name= : Nombre (solo usuarios nuevos)}
                            {--reset-password : Pedir contraseña nueva aunque el usuario ya exista}';

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
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);

            $this->info("El usuario {$email} ahora es administrador.");

            $mustReset = $this->option('reset-password')
                || $this->confirm('¿Definir una contraseña nueva para entrar en la web? (recomendado si no podés iniciar sesión)', true);

            if ($mustReset) {
                if (! $this->applyPassword($user)) {
                    return self::FAILURE;
                }
                $this->info('Contraseña actualizada. Usá ese email y la contraseña que acabás de escribir en /login');
            } else {
                $this->warn('No se cambió la contraseña: usá la que ya tenía ese email (p. ej. si se registró antes como freelancer).');
            }

            return self::SUCCESS;
        }

        $plain = $this->askForNewPassword();
        if ($plain === null) {
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
            'email_verified_at' => now(),
        ]);

        $this->info("Administrador creado: {$email}");
        $this->info('Entrá en la web con ese email y la contraseña que escribiste en /login');

        return self::SUCCESS;
    }

    private function askForNewPassword(): ?string
    {
        $plain = (string) $this->secret('Contraseña del administrador (mín. 8 caracteres)');
        $confirm = (string) $this->secret('Repetir contraseña');

        if ($plain !== $confirm) {
            $this->error('Las contraseñas no coinciden.');

            return null;
        }

        $validator = validator(['password' => $plain], ['password' => ['required', Password::defaults()]]);
        if ($validator->fails()) {
            $this->error((string) $validator->errors()->first('password'));

            return null;
        }

        return $plain;
    }

    private function applyPassword(User $user): bool
    {
        $plain = $this->askForNewPassword();
        if ($plain === null) {
            return false;
        }

        $user->update(['password' => Hash::make($plain)]);

        return true;
    }
}
