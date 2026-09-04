<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use App\Support\AppLocale;
use App\Support\HomeRedirect;
use App\Support\Impersonation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $user?->loadMissing('role');
        $locale = app()->getLocale();
        $impersonation = app(Impersonation::class);
        $impersonator = $impersonation->impersonator($request);

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'locale' => $locale,
            'dir' => AppLocale::direction($locale),
            'translations' => $this->translations($locale),
            'auth' => [
                'user' => $user === null ? null : [
                    ...$user->toArray(),
                    'role' => $user->role?->slug,
                    'role_label' => $user->role?->label(),
                ],
            ],
            'home' => app(HomeRedirect::class)->url($user),
            'can' => [
                ...collect(Permission::cases())->mapWithKeys(
                    fn (Permission $permission): array => [
                        Str::camel($permission->value) => $user?->hasPermission($permission) ?? false,
                    ],
                )->all(),
                'scanAttendance' => $user?->canScanAttendance() ?? false,
                'viewStaff' => ($user?->canViewStaff() ?? false)
                    || ($user?->canManageStaff() ?? false)
                    || ($user?->canViewTeamAttendance() ?? false),
                'viewAttendanceReports' => $user?->canViewAttendanceReports() ?? false,
                'viewRoster' => $user?->canViewRoster() ?? false,
                'viewTasks' => $user?->canViewTasks() ?? false,
                'viewLeaveRequests' => $user?->canViewLeaveRequests() ?? false,
                'impersonate' => ($user?->canStartImpersonation() ?? false)
                    && ! $impersonation->isActive($request),
            ],
            'impersonation' => [
                'active' => $impersonation->isActive($request),
                'impersonator' => $impersonator === null ? null : [
                    'id' => $impersonator->id,
                    'name' => $impersonator->name,
                ],
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function translations(string $locale): array
    {
        $path = lang_path($locale.'.json');

        if (! is_file($path)) {
            return [];
        }

        /** @var array<string, string>|null $translations */
        $translations = json_decode((string) file_get_contents($path), true);

        return $translations ?? [];
    }
}
