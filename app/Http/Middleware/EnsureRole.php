<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * @param  string  ...$roles  Valores de UserRole (p. ej. admin, freelancer, client)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        $allowed = collect($roles)
            ->map(fn (string $r) => UserRole::tryFrom($r))
            ->filter();

        if ($allowed->isEmpty() || ! $allowed->contains(fn (UserRole $r) => $r === $user->role)) {
            abort(403, 'No tenés permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
