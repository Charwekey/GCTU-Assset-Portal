<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isAuditor()) {
            abort(403);
        }

        $query = AuditLog::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('action_type')) {
            $query->where('action', $request->input('action_type'));
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        // Dynamic unique actions list for filter
        $actionTypes = AuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit-logs.index', compact('logs', 'actionTypes'));
    }
}
