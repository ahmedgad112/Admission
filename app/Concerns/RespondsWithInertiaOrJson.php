<?php

namespace App\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

trait RespondsWithInertiaOrJson
{
    /**
     * @param  array<string, mixed>  $payload
     */
    protected function flashRedirect(
        Request $request,
        string $message,
        string $redirectTo,
        array $payload = [],
        string $type = 'success',
    ): JsonResponse|RedirectResponse {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $message,
                ...$payload,
            ]);
        }

        Inertia::flash('toast', [
            'type' => $type,
            'message' => $message,
        ]);

        return redirect($redirectTo);
    }
}
