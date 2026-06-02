<?php

namespace App\Http\Controllers;

use App\Models\ShippedItem;
use App\Models\RetailCenter;
use App\Models\TransportationEvent;
use App\Traits\LogsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ShippedItemController
 *
 * Handles CRUD operations for shipped items (packages) in the UPS tracking system.
 * Both Admin and Staff can view, create, and edit items.
 * Only Admin can delete items.
 */
class ShippedItemController extends Controller
{
    use LogsAudit;

    /**
     * Display a listing of all shipped items.
     */
    public function index(Request $request)
    {
        $query = ShippedItem::with('retailCenter', 'transportationEvents');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('item_number', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        // Sorting functionality
        $sortableColumns = ['item_number', 'weight', 'destination', 'final_delivery_date', 'insurance_amount', 'dimensions', 'created_at'];
        $sortBy  = in_array($request->input('sort_by'), $sortableColumns) ? $request->input('sort_by') : 'created_at';
        $sortDir = in_array($request->input('sort_dir'), ['asc', 'desc']) ? $request->input('sort_dir') : 'desc';

        $items = $query->orderBy($sortBy, $sortDir)->paginate(10)->withQueryString();

        return view('shipped_items.index', compact('items'));
    }

    /**
     * Show the form for creating a new shipped item.
     */
    public function create()
    {
        $retailCenters = RetailCenter::all();
        $transportationEvents = TransportationEvent::all();

        return view('shipped_items.create', compact('retailCenters', 'transportationEvents'));
    }

    /**
     * Store a newly created shipped item in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_number'         => 'required|unique:shipped_items|max:100',
            'weight'              => 'required|numeric|min:0.01',
            'dimensions'          => 'required|string|max:100',
            'insurance_amount'    => 'nullable|numeric|min:0',
            'destination'         => 'required|string|max:255',
            'final_delivery_date' => 'nullable|date',
            'retail_center_id'    => 'required|exists:retail_centers,id',
            'transportation_events' => 'nullable|array',
            'transportation_events.*' => 'exists:transportation_events,id',
        ]);

        $validated['insurance_amount'] = $validated['insurance_amount'] ?? 0;

        $item = ShippedItem::create($validated);

        // Attach transportation events if provided
        if ($request->has('transportation_events')) {
            $item->transportationEvents()->sync($request->input('transportation_events'));
        }

        // Record the creation in the audit trail
        $this->logAction('CREATE', 'ShippedItem', $item->id, null, $item->toArray());

        return redirect()->route('shipped-items.index')
                         ->with('success', 'Package received and logged successfully.');
    }

    /**
     * Display a specific shipped item with its tracking history.
     */
    public function show(ShippedItem $shippedItem)
    {
        $shippedItem->load('retailCenter', 'transportationEvents');

        return view('shipped_items.show', compact('shippedItem'));
    }

    /**
     * Show the form for editing an existing shipped item.
     */
    public function edit(ShippedItem $shippedItem)
    {
        $retailCenters = RetailCenter::all();
        $transportationEvents = TransportationEvent::all();
        $shippedItem->load('transportationEvents');

        return view('shipped_items.edit', compact('shippedItem', 'retailCenters', 'transportationEvents'));
    }

    /**
     * Update the specified shipped item in the database.
     */
    public function update(Request $request, ShippedItem $shippedItem)
    {
        $validated = $request->validate([
            'weight'              => 'required|numeric|min:0.01',
            'dimensions'          => 'required|string|max:100',
            'insurance_amount'    => 'nullable|numeric|min:0',
            'destination'         => 'required|string|max:255',
            'final_delivery_date' => 'nullable|date',
            'retail_center_id'    => 'required|exists:retail_centers,id',
            'transportation_events' => 'nullable|array',
            'transportation_events.*' => 'exists:transportation_events,id',
        ]);

        $validated['insurance_amount'] = $validated['insurance_amount'] ?? 0;

        $oldValues = $shippedItem->toArray();
        $shippedItem->update($validated);

        // Sync transportation events
        if ($request->has('transportation_events')) {
            $shippedItem->transportationEvents()->sync($request->input('transportation_events'));
        } else {
            $shippedItem->transportationEvents()->detach();
        }

        // Record the update in the audit trail
        $this->logAction('UPDATE', 'ShippedItem', $shippedItem->id, $oldValues, $shippedItem->fresh()->toArray());

        return redirect()->route('shipped-items.index')
                         ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified shipped item from the database.
     * Admin-only action.
     */
    public function destroy(ShippedItem $shippedItem)
    {
        // Role Permission Check: Only Admins can delete records
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Only administrators can delete shipped items.');
        }

        $oldValues = $shippedItem->toArray();
        $itemId = $shippedItem->id;

        $shippedItem->transportationEvents()->detach();
        $shippedItem->delete();

        // Record the deletion in the audit trail
        $this->logAction('DELETE', 'ShippedItem', $itemId, $oldValues, null);

        return redirect()->route('shipped-items.index')
                         ->with('success', 'Package deleted permanently.');
    }
}
