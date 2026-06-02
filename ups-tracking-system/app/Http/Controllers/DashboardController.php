<?php

namespace App\Http\Controllers;

use App\Models\ShippedItem;
use App\Models\RetailCenter;
use App\Models\TransportationEvent;
use App\Models\AuditLog;
use Illuminate\Http\Request;

/**
 * DashboardController
 *
 * Aggregates system statistics for the main dashboard view.
 */
class DashboardController extends Controller
{
    /**
     * Display the dashboard with aggregated statistics.
     */
    public function index()
    {
        $stats = [
            'total_items'    => ShippedItem::count(),
            'total_centers'  => RetailCenter::count(),
            'total_events'   => TransportationEvent::count(),
            'total_logs'     => AuditLog::count(),
            'pending_items'  => ShippedItem::whereNull('final_delivery_date')->count(),
            'delivered_items' => ShippedItem::whereNotNull('final_delivery_date')->count(),
        ];

        $recentLogs = AuditLog::with('user')
                              ->orderBy('created_at', 'desc')
                              ->take(10)
                              ->get();

        $recentItems = ShippedItem::with('retailCenter')
                                  ->orderBy('created_at', 'desc')
                                  ->take(5)
                                  ->get();

        return view('dashboard', compact('stats', 'recentLogs', 'recentItems'));
    }
}
