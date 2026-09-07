<?php

namespace App\Http\Responses;

use App\Support\HomeRedirect;
use Illuminate\Http\JsonResponse;
use Laravel\Passkeys\Contracts\PasskeyLoginResponse as PasskeyLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class PasskeyLoginResponse implements PasskeyLoginResponseContract
{
    public function toResponse($request): JsonResponse|Response
    {
        $user = $request->user();

        $redirect = $user?->must_change_password
            ? redirect()->route('security.edit')
            : redirect()->intended(app(HomeRedirect::class)->url($user));

        if ($request->wantsJson()) {
            return response()->json(['redirect' => $redirect->getTargetUrl()]);
        }

        return $redirect;
    }
}
