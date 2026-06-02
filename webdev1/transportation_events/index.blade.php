@extends('layouts.app')
@section('title', 'Transportation Events')

@php
    $currentSort = request('sort_by');
    $currentDir  = request('sort_dir', 'asc');

    $sortOptions = [
        'schedule_number' => 'Schedule Number',
        'type'            => 'Type',
        'delivery_route'  => 'Delivery Route',
    ];
@endphp

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h1><i class="bi bi-truck me-2"></i>Transportation Events</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Transportation Events</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('transportation-events.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Schedule Event
    </a>
</div>

{{-- Search & Filter Bar --}}
<div class="card data-table-card mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex gap-2 align-items-center">
            {{-- Search --}}
            <form action="{{ route('transportation-events.index') }}" method="GET" class="d-flex gap-2 flex-grow-1">
                @if($currentSort)
                    <input type="hidden" name="sort_by" value="{{ $currentSort }}">
                    <input type="hidden" name="sort_dir" value="{{ $currentDir }}">
                @endif
                <div class="input-group input-group-sm flex-grow-1">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by schedule number, type, or route..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                @if(request('search'))
                    <a href="{{ route('transportation-events.index', $currentSort ? ['sort_by' => $currentSort, 'sort_dir' => $currentDir] : []) }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                @endif
            </form>

            {{-- Sort Controls --}}
            <div class="d-flex align-items-center gap-0">
                <div class="dropdown">
                    <button class="btn btn-sm {{ $currentSort ? 'btn-dark' : 'btn-outline-dark' }} filter-sort-btn rounded-end-0" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-funnel{{ $currentSort ? '-fill' : '' }} me-1"></i>{{ $currentSort ? ($sortOptions[$currentSort] ?? $currentSort) : 'Sort' }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-end sort-dropdown-menu p-2">
                        <div class="sort-dropdown-title">Sort by</div>
                        @foreach($sortOptions as $column => $label)
                            @php
                                $isActive = $currentSort === $column;
                                $params = request()->except(['sort_by', 'sort_dir']);
                                if (!$isActive) {
                                    $params['sort_by'] = $column;
                                    $params['sort_dir'] = 'asc';
                                }
                            @endphp
                            <a href="{{ route('transportation-events.index', $isActive ? request()->except(['sort_by', 'sort_dir']) : $params) }}" class="sort-item {{ $isActive ? 'active' : '' }}">
                                <span class="sort-item-label">{{ $label }}</span>
                                @if($isActive)
                                    <i class="bi bi-check-lg"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                @php
                    $toggleDir = $currentDir === 'asc' ? 'desc' : 'asc';
                    $toggleParams = array_merge(request()->except(['sort_dir']), ['sort_dir' => $toggleDir]);
                    if (!$currentSort) { $toggleParams = request()->all(); }
                @endphp
                <a href="{{ $currentSort ? route('transportation-events.index', $toggleParams) : '#' }}"
                   class="btn btn-sm {{ $currentSort ? 'btn-dark' : 'btn-outline-dark' }} sort-dir-toggle rounded-start-0 {{ !$currentSort ? 'disabled' : '' }}"
                   title="{{ $currentDir === 'asc' ? 'Ascending — click to switch to Descending' : 'Descending — click to switch to Ascending' }}">
                    <i class="bi bi-arrow-{{ $currentDir === 'asc' ? 'up' : 'down' }}"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card data-table-card">
    <div class="card-body p-0 table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Schedule #</th>
                    <th>Type</th>
                    <th>Delivery Route</th>
                    <th>Packages</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr>
                    <td class="fw-semibold">{{ $event->schedule_number }}</td>
                    <td>
                        <span class="badge badge-status
                            @if($event->type === 'Flight') bg-primary bg-opacity-10 text-primary
                            @elseif($event->type === 'Truck') bg-success bg-opacity-10 text-success
                            @elseif($event->type === 'Ship') bg-info bg-opacity-10 text-info
                            @else bg-secondary bg-opacity-10 text-secondary
                            @endif">
                            @if($event->type === 'Flight')<i class="bi bi-airplane me-1"></i>
                            @elseif($event->type === 'Truck')<i class="bi bi-truck me-1"></i>
                            @elseif($event->type === 'Ship')<i class="bi bi-water me-1"></i>
                            @endif{{ $event->type }}
                        </span>
                    </td>
                    <td>{{ $event->delivery_route }}</td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary badge-status">{{ $event->shippedItems()->count() }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('transportation-events.show', $event) }}" class="btn btn-sm btn-outline-info btn-action"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('transportation-events.edit', $event) }}" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                        @if(auth()->user()->isAdmin())
                        <form action="{{ route('transportation-events.destroy', $event) }}" method="POST" class="d-inline-block btn-delete-confirm">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-action"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-truck"></i>
                            <h5>No Transportation Events Found</h5>
                            <p>Schedule a new transportation event to start routing packages.</p>
                            <a href="{{ route('transportation-events.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Schedule Event</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($events->hasPages())
    <div class="card-footer">{{ $events->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
