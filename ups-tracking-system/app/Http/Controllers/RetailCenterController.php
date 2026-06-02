<?php

namespace App\Http\Controllers;

use App\Models\RetailCenter;
use App\Traits\LogsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * RetailCenterController
 *
 * Handles CRUD operations for UPS retail centers (intake locations).
 * Both Admin and Staff can view, create, and edit centers.
 * Only Admin can delete centers.
 */
class RetailCenterController extends Controller
{
    use LogsAudit;

    /**
     * Display a listing of all retail centers.
     */
    public function index(Request $request)
    {
        $query = RetailCenter::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('unique_id', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Sorting functionality
        $sortableColumns = ['unique_id', 'type', 'address', 'created_at'];
        $sortBy  = in_array($request->input('sort_by'), $sortableColumns) ? $request->input('sort_by') : 'created_at';
        $sortDir = in_array($request->input('sort_dir'), ['asc', 'desc']) ? $request->input('sort_dir') : 'desc';

        $centers = $query->orderBy($sortBy, $sortDir)->paginate(10)->withQueryString();

        return view('retail_centers.index', compact('centers'));
    }

    /**
     * Show the form for creating a new retail center.
     */
    public function create()
    {
        return view('retail_centers.create');
    }

    /**
     * Store a newly created retail center in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unique_id' => 'required|unique:retail_centers,unique_id|max:100',
            'type'      => 'required|string|max:50',
            'address'   => 'required|string',
        ]);

        $center = RetailCenter::create($validated);

        $this->logAction('CREATE', 'RetailCenter', $center->id, null, $center->toArray());

        return redirect()->route('retail-centers.index')
                         ->with('success', 'Retail Center created successfully.');
    }

    /**
     * Display a specific retail center with its shipped items.
     */
    public function show(RetailCenter $retailCenter)
    {
        $retailCenter->load('shippedItems');

        return view('retail_centers.show', compact('retailCenter'));
    }

    /**
     * Show the form for editing an existing retail center.
     */
    public function edit(RetailCenter $retailCenter)
    {
        return view('retail_centers.edit', compact('retailCenter'));
    }

    /**
     * Update the specified retail center in the database.
     */
    public function update(Request $request, RetailCenter $retailCenter)
    {
        $validated = $request->validate([
            'type'    => 'required|string|max:50',
            'address' => 'required|string',
        ]);

        $oldValues = $retailCenter->toArray();
        $retailCenter->update($validated);

        $this->logAction('UPDATE', 'RetailCenter', $retailCenter->id, $oldValues, $retailCenter->fresh()->toArray());

        return redirect()->route('retail-centers.index')
                         ->with('success', 'Retail Center updated successfully.');
    }

    /**
     * Remove the specified retail center from the database.
     * Admin-only action.
     */
    public function destroy(RetailCenter $retailCenter)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Only administrators can delete retail centers.');
        }

        $oldValues = $retailCenter->toArray();
        $centerId = $retailCenter->id;

        $retailCenter->delete();

        $this->logAction('DELETE', 'RetailCenter', $centerId, $oldValues, null);

        return redirect()->route('retail-centers.index')
                         ->with('success', 'Retail Center deleted successfully.');
    }
}
