<?php

namespace App\Providers;

use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\RolePermissionCatalog;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RolePermissionCatalog::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiting();
        $this->configureActivityLogging();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Schema::defaultStringLength(191);

        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('qr-scan', function (Request $request) {
            return Limit::perMinute((int) config('attendance.qr_scan_per_minute', 10))
                ->by('qr-scan|'.($request->user()?->id ?? $request->ip()));
        });
    }

    protected function configureActivityLogging(): void
    {
        Event::listen(Login::class, function (Login $event): void {
            if (! $event->user instanceof User) {
                return;
            }

            ActivityLogger::record('logged_in', $event->user, ['name' => $event->user->name], $event->user);
        });

        Event::listen(Logout::class, function (Logout $event): void {
            if (! $event->user instanceof User) {
                return;
            }

            ActivityLogger::record('logged_out', $event->user, ['name' => $event->user->name], $event->user);
        });

        Event::listen(Failed::class, function (Failed $event): void {
            $user = $event->user instanceof User ? $event->user : null;
            $email = is_string($event->credentials['email'] ?? null)
                ? $event->credentials['email']
                : '';

            ActivityLogger::record('login_failed', $user, [
                'name' => $user?->name ?? $email,
                'email' => $email,
            ], $user);
        });
    }
}
