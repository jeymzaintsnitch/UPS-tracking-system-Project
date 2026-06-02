@extends('layouts.app')
@section('title', 'Package Details')

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h1><i class="bi bi-box-seam me-2"></i>Package: {{ $shippedItem->item_number }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shipped-items.index') }}">Shipped Items</a></li>
                <li class="breadcrumb-item active">{{ $shippedItem->item_number }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('shipped-items.edit', $shippedItem) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('shipped-items.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Package Details Card --}}
    <div class="col-lg-7">
        <div class="card form-card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2"></i>Package Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Item Number</label>
                        <div class="fw-bold">{{ $shippedItem->item_number }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Weight</label>
                        <div class="fw-bold">{{ $shippedItem->weight }} kg</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Dimensions</label>
                        <div class="fw-bold">{{ $shippedItem->dimensions }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Insurance</label>
                        <div class="fw-bold">₱{{ number_format($shippedItem->insurance_amount, 2) }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small">Destination</label>
                        <div class="fw-bold">{{ $shippedItem->destination }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Delivery Date</label>
                        <div>
                            @if($shippedItem->final_delivery_date)
                                <span class="badge bg-success bg-opacity-10 text-success badge-status">
                                    {{ \Carbon\Carbon::parse($shippedItem->final_delivery_date)->format('F d, Y') }}
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning badge-status">Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Retail Center</label>
                        <div class="fw-bold">
                            {{ $shippedItem->retailCenter->unique_id ?? 'N/A' }}
                            <span class="text-muted small">({{ $shippedItem->retailCenter->type ?? '' }})</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Created</label>
                        <div class="text-muted small">{{ $shippedItem->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Last Updated</label>
                        <div class="text-muted small">{{ $shippedItem->updated_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tracking Timeline --}}
    <div class="col-lg-5">
        <div class="card form-card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-geo-alt me-2"></i>Tracking Timeline</h6>
            </div>
            <div class="card-body">
                @if($shippedItem->transportationEvents->count() > 0)
                    <div class="tracking-timeline">
                        @foreach($shippedItem->transportationEvents as $index => $event)
                        <div class="timeline-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <strong class="small">{{ $event->schedule_number }}</strong>
                                    <span class="badge bg-primary bg-opacity-10 text-primary badge-status">{{ $event->type }}</span>
                                </div>
                                <div class="text-muted small mt-1">
                                    <i class="bi bi-signpost-2 me-1"></i>{{ $event->delivery_route }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-3">
                        <i class="bi bi-geo-alt" style="font-size:2rem;"></i>
                        <p class="small mt-2 mb-0">No transportation events assigned yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
