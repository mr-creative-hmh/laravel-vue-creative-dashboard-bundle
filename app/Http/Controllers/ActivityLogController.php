<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('logs.view');

        $query = Activity::with(['causer', 'subject']);

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('log_name', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%");
            });
        }

        // Event filter
        if ($event = $request->input('event')) {
            $query->where('event', $event);
        }

        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->through(function ($log) {
                return [
                    'id' => $log->id,
                    'log_name' => $log->log_name,
                    'description' => $log->description,
                    'event' => $log->event,
                    'subject_type' => $log->subject_type ? class_basename($log->subject_type) : null,
                    'subject_id' => $log->subject_id,
                    'causer' => $log->causer ? [
                        'id' => $log->causer->id,
                        'name' => $log->causer->name,
                        'email' => $log->causer->email,
                    ] : null,
                    'properties' => $log->properties,
                    'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                    'created_at_human' => $log->created_at->diffForHumans(),
                ];
            });

        return Inertia::render('ActivityLogs/Index', [
            'logs' => $logs,
            'filters' => $request->only(['search', 'event']),
        ]);
    }

    /**
     * Revert / Undo the logged action.
     */
    public function undo(Request $request, Activity $activity): RedirectResponse
    {
        Gate::authorize('logs.view');

        $properties = $activity->properties;

        if ($activity->event === 'updated' && $activity->subject && isset($properties['old'])) {
            $activity->subject->update($properties['old']);
            activity()
                ->causedBy($request->user())
                ->performedOn($activity->subject)
                ->log("Undid activity #{$activity->id}");

            return back()->with('success', 'Action reverted successfully.');
        }

        if ($activity->event === 'created' && $activity->subject) {
            $activity->subject->delete();
            activity()
                ->causedBy($request->user())
                ->log("Undid creation activity #{$activity->id}");

            return back()->with('success', 'Created record undone successfully.');
        }

        return back()->with('error', 'Unable to undo this log action.');
    }
}
