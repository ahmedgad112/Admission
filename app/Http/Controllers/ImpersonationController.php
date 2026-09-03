<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\HomeRedirect;
use App\Support\Impersonation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ImpersonationController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function store(Request $request, User $user, Impersonation $impersonation): JsonResponse|RedirectResponse
    {
        abort_if($impersonation->isActive($request), 403);
        $this->authorize('impersonate', $user);

        $actor = $request->user();
        abort_unless($actor !== null, 403);

        $impersonation->start($request, $actor, $user);

        ActivityLogger::record('impersonated', $user, ['name' => $user->name], $actor);

        return $this->flashRedirect(
            $request,
            __('flash.impersonation.started', ['name' => $user->name]),
            app(HomeRedirect::class)->url($user),
        );
    }

    public function destroy(Request $request, Impersonation $impersonation): JsonResponse|RedirectResponse
    {
        abort_unless($impersonation->isActive($request), 403);

        $target = $request->user();
        abort_unless($target instanceof User, 403);

        $actor = $impersonation->stop($request);

        abort_unless($actor !== null, 403);

        ActivityLogger::record(
            'impersonation_stopped',
            $target,
            ['name' => $target->name],
            $actor,
        );

        return $this->flashRedirect(
            $request,
            __('flash.impersonation.stopped', ['name' => $actor->name]),
            route('staff.index'),
        );
    }
}
