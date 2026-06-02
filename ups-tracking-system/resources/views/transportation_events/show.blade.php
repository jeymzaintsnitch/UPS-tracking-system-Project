@extends('layouts.app')
@section('title', 'Transportation Event Details')

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h1><i class="bi bi-truck me-2"></i>{{ $transportationEvent->schedule_number }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('transportation-events.index') }}">Transportation Events</a></li>
                <li class="breadcrumb-item active">{{ $transportationEvent->schedule_number }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('transportation-events.edit', $transportationEvent) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Event Information</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted small">Schedule Number</label>
                    <div class="fw-bold">{{ $transportationEvent->schedule_number }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Type</label>
                    <div>
                        <span class="badge badge-status
                            @if($transportationEvent->type === 'Flight') bg-primary bg-opacity-10 text-primary
                            @elseif($transportationEvent->type === 'Truck') bg-success bg-opacity-10 text-success
                            @else bg-info bg-opacity-10 text-info
                            @endif">{{ $transportationEvent->type }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Delivery Route</label>
                    <div class="fw-bold">{{ $transportationEvent->delivery_route }}</div>
                </div>
                <div class="mb-0">
                    <label class="form-label text-muted small">Created</label>
                    <div class="small text-muted">{{ $transportationEvent->created_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card data-table-card">
            <div class="card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-box-seam me-2"></i>Packages on this Event ({{ $transportationEvent->shippedItems->count() }})</h6></div>
            <div class="card-body p-0 table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Item #</th><th>Destination</th><th>Weight</th></tr></thead>
                    <tbody>
                        @forelse($transportationEvent->shippedItems as $item)
                        <tr>
                            <td><a href="{{ route('shipped-items.show', $item) }}" class="text-decoration-none fw-semibold">{{ $item->item_number }}</a></td>
                            <td>{{ Str::limit($item->destination, 30) }}</td>
                            <td>{{ $item->weight }} kg</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No packages assigned to this event</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
