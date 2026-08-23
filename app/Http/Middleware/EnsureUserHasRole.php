<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use ValueError;

class EnsureUserHasRole
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_unless($user !== null, 403);

        try {
            $allowed = array_map(
                fn (string $role): UserRole => UserRole::from($role),
                $roles,
            );
        } catch (ValueError) {
            abort(403);
        }

        abort_unless($user->hasRole(...$allowed), 403);

        return $next($request);
    }
}
