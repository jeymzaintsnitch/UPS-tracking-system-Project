@extends('layouts.app')
@section('title', 'Retail Center Details')

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h1><i class="bi bi-building me-2"></i>{{ $retailCenter->unique_id }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('retail-centers.index') }}">Retail Centers</a></li>
                <li class="breadcrumb-item active">{{ $retailCenter->unique_id }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('retail-centers.edit', $retailCenter) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Center Information</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted small">Unique ID</label>
                    <div class="fw-bold">{{ $retailCenter->unique_id }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Type</label>
                    <div><span class="badge bg-info bg-opacity-10 text-info">{{ $retailCenter->type }}</span></div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Address</label>
                    <div>{{ $retailCenter->address }}</div>
                </div>
                <div class="mb-0">
                    <label class="form-label text-muted small">Created</label>
                    <div class="small text-muted">{{ $retailCenter->created_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card data-table-card">
            <div class="card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-box-seam me-2"></i>Packages at this Center ({{ $retailCenter->shippedItems->count() }})</h6></div>
            <div class="card-body p-0 table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Item #</th><th>Destination</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($retailCenter->shippedItems as $item)
                        <tr>
                            <td><a href="{{ route('shipped-items.show', $item) }}" class="text-decoration-none fw-semibold">{{ $item->item_number }}</a></td>
                            <td>{{ Str::limit($item->destination, 30) }}</td>
                            <td>
                                @if($item->final_delivery_date)
                                    <span class="badge bg-success bg-opacity-10 text-success badge-status">Delivered</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning badge-status">In Transit</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No packages at this center</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
