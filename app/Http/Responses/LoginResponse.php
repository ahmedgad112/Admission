<?php

namespace App\Http\Responses;

use App\Support\HomeRedirect;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): JsonResponse|Response
    {
        $user = $request->user();

        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        if ($user?->must_change_password) {
            return redirect()->route('security.edit');
        }

        return redirect()->intended(app(HomeRedirect::class)->url($user));
    }
}
