@extends('layouts.app')
@section('title', 'Audit Trail')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-shield-lock-fill me-2"></i>Audit Trail</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Audit Logs</li>
        </ol>
    </nav>
</div>

{{-- Filters --}}
<div class="card data-table-card mb-3">
    <div class="card-body py-2 px-3">
        <form action="{{ route('audit-logs.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
            <div class="input-group input-group-sm" style="min-width:200px;flex:1;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search by user or entity..." value="{{ request('search') }}">
            </div>
            <select name="action" class="form-select form-select-sm" style="max-width:130px;">
                <option value="">All Actions</option>
                <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>CREATE</option>
                <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>UPDATE</option>
                <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
            </select>
            <select name="entity" class="form-select form-select-sm" style="max-width:180px;">
                <option value="">All Entities</option>
                <option value="ShippedItem" {{ request('entity') == 'ShippedItem' ? 'selected' : '' }}>Shipped Item</option>
                <option value="RetailCenter" {{ request('entity') == 'RetailCenter' ? 'selected' : '' }}>Retail Center</option>
                <option value="TransportationEvent" {{ request('entity') == 'TransportationEvent' ? 'selected' : '' }}>Transportation Event</option>
                <option value="User" {{ request('entity') == 'User' ? 'selected' : '' }}>User</option>
            </select>
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            @if(request()->hasAny(['search', 'action', 'entity']))
                <a href="{{ route('audit-logs.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="card data-table-card">
    <div class="card-body p-0 table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Entity</th>
                    <th>Entity ID</th>
                    <th class="text-end">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-muted small">{{ $log->created_at->format('M d, Y h:i:s A') }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;background:#351C15;color:#FFB500;font-size:0.6rem;font-weight:600;">
                                {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                            </div>
                            <span class="small fw-medium">{{ $log->user->name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-status
                            @if($log->action === 'CREATE') bg-success bg-opacity-10 text-success
                            @elseif($log->action === 'UPDATE') bg-info bg-opacity-10 text-info
                            @elseif($log->action === 'DELETE') bg-danger bg-opacity-10 text-danger
                            @endif">{{ $log->action }}</span>
                    </td>
                    <td class="fw-medium small">{{ $log->entity }}</td>
                    <td><code>#{{ $log->entity_id }}</code></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary btn-action audit-toggle" data-target="audit-{{ $log->id }}">
                            View Details
                        </button>
                    </td>
                </tr>
                {{-- Expandable Details Row --}}
                <tr>
                    <td colspan="6" class="p-0 border-0">
                        <div id="audit-{{ $log->id }}" class="audit-values">
                            <div class="px-3 pb-3">
                                <div class="row g-2">
                                    @if($log->old_values)
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-semibold mb-1">
                                            <i class="bi bi-dash-circle text-danger me-1"></i>Old Values
                                        </label>
                                        <pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                    @endif
                                    @if($log->new_values)
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-semibold mb-1">
                                            <i class="bi bi-plus-circle text-success me-1"></i>New Values
                                        </label>
                                        <pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-shield-lock"></i>
                            <h5>No Audit Logs Found</h5>
                            <p>System activity will appear here as users perform actions.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-footer">{{ $logs->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
