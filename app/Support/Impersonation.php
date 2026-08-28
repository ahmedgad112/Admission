<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Impersonation
{
    public const SESSION_KEY = 'impersonator_id';

    public function start(Request $request, User $actor, User $target): void
    {
        Auth::login($target);
        $request->session()->put(self::SESSION_KEY, $actor->id);
    }

    public function stop(Request $request): ?User
    {
        $impersonatorId = $request->session()->pull(self::SESSION_KEY);

        if (! is_numeric($impersonatorId)) {
            return null;
        }

        $impersonator = User::query()->find((int) $impersonatorId);

        if ($impersonator === null) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return null;
        }

        Auth::login($impersonator);

        return $impersonator;
    }

    public function isActive(Request $request): bool
    {
        return $request->session()->has(self::SESSION_KEY);
    }

    public function impersonator(Request $request): ?User
    {
        $id = $request->session()->get(self::SESSION_KEY);

        if (! is_numeric($id)) {
            return null;
        }

        return User::query()->find((int) $id);
    }
}
