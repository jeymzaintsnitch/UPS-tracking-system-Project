@extends('layouts.app')
@section('title', 'Authentication Logs')

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h1><i class="bi bi-shield-lock me-2"></i>Authentication Logs</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Authentication Logs</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card data-table-card">
    <div class="card-body p-0 table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Event Type</th>
                    <th>User / Email</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-nowrap">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                    <td>
                        @if($log->event_type === 'login')
                            <span class="badge bg-success bg-opacity-10 text-success badge-status"><i class="bi bi-box-arrow-in-right me-1"></i>Login</span>
                        @elseif($log->event_type === 'logout')
                            <span class="badge bg-secondary bg-opacity-10 text-secondary badge-status"><i class="bi bi-box-arrow-left me-1"></i>Logout</span>
                        @elseif($log->event_type === 'failed')
                            <span class="badge bg-danger bg-opacity-10 text-danger badge-status"><i class="bi bi-x-circle me-1"></i>Failed Attempt</span>
                        @else
                            <span class="badge bg-info bg-opacity-10 text-info badge-status">{{ ucfirst($log->event_type) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($log->user)
                            <div class="fw-semibold">{{ $log->user->name }}</div>
                            <div class="text-muted small">{{ $log->user->email }}</div>
                        @else
                            <div class="text-muted fst-italic">{{ $log->email_used ?? 'Unknown User' }}</div>
                        @endif
                    </td>
                    <td><span class="text-muted font-monospace small">{{ $log->ip_address ?? 'N/A' }}</span></td>
                    <td><span class="text-muted small d-inline-block text-truncate" style="max-width: 200px;" title="{{ $log->user_agent }}">{{ $log->user_agent ?? 'N/A' }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-shield-check display-4 d-block mb-3 opacity-50"></i>
                        <h5>No Authentication Events Found</h5>
                        <p class="mb-0">There are no authentication events logged yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-footer">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
