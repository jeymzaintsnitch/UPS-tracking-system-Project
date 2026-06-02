<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AuditLogController
 *
 * Displays the audit trail log for administrators.
 * Admin-only access (also enforced via route middleware).
 */
class AuditLogController extends Controller
{
    /**
     * Display a listing of all audit log entries.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Filter by action type
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Filter by entity
        if ($request->filled('entity')) {
            $query->where('entity', $request->input('entity'));
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })->orWhere('entity', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('audit_logs.index', compact('logs'));
    }
}
