<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('admin.activity-logs', compact('logs'));
    }

    /**
     * Helper to log an activity from anywhere.
     */
    public static function log(string $action, ?string $model = null, ?int $modelId = null): void
    {
        ActivityLog::create([
            'user_id'  => auth()->id(),
            'action'   => $action,
            'model'    => $model,
            'model_id' => $modelId,
        ]);
    }
}
