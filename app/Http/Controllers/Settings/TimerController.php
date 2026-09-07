<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateTimerSettingsRequest;
use App\Models\Attendance;
use App\Services\AttendanceSettings;
use App\Support\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TimerController extends Controller
{
    public function __construct(public AttendanceSettings $settings) {}

    public function edit(): Response
    {
        $this->authorize('manageKiosk', Attendance::class);

        return Inertia::render('settings/Timer', [
            'qrTtlSeconds' => $this->settings->qrTtlSeconds(),
            'minTtl' => $this->settings->minTtl(),
            'maxTtl' => $this->settings->maxTtl(),
            'presets' => $this->settings->presets(),
        ]);
    }

    public function update(UpdateTimerSettingsRequest $request): JsonResponse|RedirectResponse
    {
        $seconds = $this->settings->updateQrTtlSeconds((int) $request->validated('qr_ttl_seconds'));

        ActivityLogger::record('timer_updated', null, [
            'name' => (string) $seconds,
            'qr_ttl_seconds' => $seconds,
        ]);

        $message = __('flash.timer.updated', ['seconds' => $seconds]);

        if ($request->expectsJson()) {
            return response()->json([
                'qr_ttl_seconds' => $seconds,
                'message' => $message,
            ]);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => $message]);

        return to_route('timer.edit');
    }
}
