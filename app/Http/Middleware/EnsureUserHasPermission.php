<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        abort_unless($user !== null, 403);

        $allowed = false;

        foreach ($permissions as $permission) {
            $enum = Permission::tryFrom($permission);

            if ($enum !== null && $user->hasPermission($enum)) {
                $allowed = true;
                break;
            }
        }

        abort_unless($allowed, 403);

        return $next($request);
    }
}
