<?php

namespace App\Http\Responses;

use App\Support\HomeRedirect;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request): JsonResponse|Response
    {
        $user = $request->user();

        if ($request->wantsJson()) {
            return new JsonResponse('', 204);
        }

        if ($user?->must_change_password) {
            return redirect()->route('security.edit');
        }

        return redirect()->intended(app(HomeRedirect::class)->url($user));
    }
}
