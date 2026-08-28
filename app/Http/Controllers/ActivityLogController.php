<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ActivityLogController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', ActivityLog::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $logs = ActivityLog::query()
            ->with(['causer:id,name,email'])
            ->tap(fn ($query) => $user->constrainActivityVisibility($query))
            ->when($request->string('search')->isNotEmpty(), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($builder) use ($search): void {
                    $builder->where('event', 'like', '%'.$search.'%')
                        ->orWhere('ip_address', 'like', '%'.$search.'%')
                        ->orWhereHas(
                            'causer',
                            fn ($causer) => $causer->where('name', 'like', '%'.$search.'%')
                                ->orWhere('email', 'like', '%'.$search.'%'),
                        );
                });
            })
            ->when($request->string('event')->isNotEmpty(), fn ($query) => $query->where('event', $request->string('event')))
            ->latest()
            ->paginate(20)
            ->through(fn (ActivityLog $log) => [
                'id' => $log->id,
                'event' => $log->event,
                'description' => $log->description(),
                'subject_type' => $log->subjectKey(),
                'properties' => $log->properties,
                'changes' => is_array($log->properties['changes'] ?? null)
                    ? $log->properties['changes']
                    : null,
                'causer' => $log->causer === null ? null : [
                    'id' => $log->causer->id,
                    'name' => $log->causer->name,
                    'email' => $log->causer->email,
                ],
                'ip_address' => $log->ip_address,
                'created_at' => $log->created_at?->toIso8601String(),
            ])
            ->withQueryString();

        return Inertia::render('activity-logs/Index', [
            'logs' => $logs,
            'filters' => [
                'search' => $request->string('search')->toString(),
                'event' => $request->string('event')->toString(),
            ],
            'events' => [
                'created',
                'updated',
                'deleted',
                'checked_in',
                'checked_out',
                'roster_synced',
                'assignees_synced',
                'qr_created',
                'qr_expired',
                'session_opened',
                'session_closed',
                'logged_in',
                'logged_out',
                'login_failed',
                'impersonated',
                'impersonation_stopped',
            ],
        ]);
    }
}
