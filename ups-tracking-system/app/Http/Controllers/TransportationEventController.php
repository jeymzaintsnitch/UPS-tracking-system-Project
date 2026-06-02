<?php

namespace App\Http\Controllers;

use App\Models\TransportationEvent;
use App\Traits\LogsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * TransportationEventController
 *
 * Handles CRUD operations for transportation events (flights, trucks, etc.).
 * Both Admin and Staff can view, create, and edit events.
 * Only Admin can delete events.
 */
class TransportationEventController extends Controller
{
    use LogsAudit;

    /**
     * Display a listing of all transportation events.
     */
    public function index(Request $request)
    {
        $query = TransportationEvent::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('schedule_number', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('delivery_route', 'like', "%{$search}%");
            });
        }

        // Sorting functionality
        $sortableColumns = ['schedule_number', 'type', 'delivery_route', 'created_at'];
        $sortBy  = in_array($request->input('sort_by'), $sortableColumns) ? $request->input('sort_by') : 'created_at';
        $sortDir = in_array($request->input('sort_dir'), ['asc', 'desc']) ? $request->input('sort_dir') : 'desc';

        $events = $query->orderBy($sortBy, $sortDir)->paginate(10)->withQueryString();

        return view('transportation_events.index', compact('events'));
    }

    /**
     * Show the form for creating a new transportation event.
     */
    public function create()
    {
        return view('transportation_events.create');
    }

    /**
     * Store a newly created transportation event in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_number' => 'required|unique:transportation_events,schedule_number|max:100',
            'type'            => 'required|string|max:50',
            'delivery_route'  => 'required|string|max:255',
        ]);

        $event = TransportationEvent::create($validated);

        $this->logAction('CREATE', 'TransportationEvent', $event->id, null, $event->toArray());

        return redirect()->route('transportation-events.index')
                         ->with('success', 'Transportation Event created successfully.');
    }

    /**
     * Display a specific transportation event with its associated items.
     */
    public function show(TransportationEvent $transportationEvent)
    {
        $transportationEvent->load('shippedItems');

        return view('transportation_events.show', compact('transportationEvent'));
    }

    /**
     * Show the form for editing an existing transportation event.
     */
    public function edit(TransportationEvent $transportationEvent)
    {
        return view('transportation_events.edit', compact('transportationEvent'));
    }

    /**
     * Update the specified transportation event in the database.
     */
    public function update(Request $request, TransportationEvent $transportationEvent)
    {
        $validated = $request->validate([
            'type'           => 'required|string|max:50',
            'delivery_route' => 'required|string|max:255',
        ]);

        $oldValues = $transportationEvent->toArray();
        $transportationEvent->update($validated);

        $this->logAction('UPDATE', 'TransportationEvent', $transportationEvent->id, $oldValues, $transportationEvent->fresh()->toArray());

        return redirect()->route('transportation-events.index')
                         ->with('success', 'Transportation Event updated successfully.');
    }

    /**
     * Remove the specified transportation event from the database.
     * Admin-only action.
     */
    public function destroy(TransportationEvent $transportationEvent)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Only administrators can delete transportation events.');
        }

        $oldValues = $transportationEvent->toArray();
        $eventId = $transportationEvent->id;

        $transportationEvent->shippedItems()->detach();
        $transportationEvent->delete();

        $this->logAction('DELETE', 'TransportationEvent', $eventId, $oldValues, null);

        return redirect()->route('transportation-events.index')
                         ->with('success', 'Transportation Event deleted successfully.');
    }
}
