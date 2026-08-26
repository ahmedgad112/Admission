<?php

namespace App\Http\Middleware;

use App\Support\AppLocale;
use Illuminate\Http\Request;
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
        $locale = app()->getLocale();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'locale' => $locale,
            'dir' => AppLocale::direction($locale),
            'translations' => $this->translations($locale),
            'auth' => [
                'user' => $user,
            ],
            'can' => [
                'managePermissions' => $user?->canManagePermissions() ?? false,
                'manageKiosk' => $user?->canManageKiosk() ?? false,
                'manageStaff' => $user?->canManageStaff() ?? false,
                'manageTasks' => $user?->canManageTasks() ?? false,
                'viewTeamAttendance' => $user?->canViewTeamAttendance() ?? false,
                'reviewLeaveRequests' => $user?->canReviewLeaveRequests() ?? false,
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
