@extends('layouts.app')
@section('title', 'Shipped Items')

@php
    $currentSort = request('sort_by');
    $currentDir  = request('sort_dir', 'asc');

    $sortOptions = [
        'item_number'        => 'Item Number',
        'weight'             => 'Weight',
        'destination'        => 'Destination',
        'final_delivery_date'=> 'Delivery Date',
        'dimensions'         => 'Dimensions',
    ];
@endphp

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h1><i class="bi bi-box-seam me-2"></i>Shipped Items</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Shipped Items</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('shipped-items.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Receive New Package
    </a>
</div>

{{-- Search & Filter Bar --}}
<div class="card data-table-card mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex gap-2 align-items-center">
            {{-- Search --}}
            <form action="{{ route('shipped-items.index') }}" method="GET" class="d-flex gap-2 flex-grow-1">
                @if($currentSort)
                    <input type="hidden" name="sort_by" value="{{ $currentSort }}">
                    <input type="hidden" name="sort_dir" value="{{ $currentDir }}">
                @endif
                <div class="input-group input-group-sm flex-grow-1">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by item number or destination..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                @if(request('search'))
                    <a href="{{ route('shipped-items.index', $currentSort ? ['sort_by' => $currentSort, 'sort_dir' => $currentDir] : []) }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                @endif
            </form>

            {{-- Sort Controls --}}
            <div class="d-flex align-items-center gap-0">
                {{-- Sort Dropdown (pick column) --}}
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
                                if ($isActive) {
                                    // Clicking active item clears the sort
                                } else {
                                    $params['sort_by'] = $column;
                                    $params['sort_dir'] = 'asc';
                                }
                            @endphp
                            <a href="{{ route('shipped-items.index', $isActive ? request()->except(['sort_by', 'sort_dir']) : $params) }}" class="sort-item {{ $isActive ? 'active' : '' }}">
                                <span class="sort-item-label">{{ $label }}</span>
                                @if($isActive)
                                    <i class="bi bi-check-lg"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- ASC/DESC Toggle Button --}}
                @php
                    $toggleDir = $currentDir === 'asc' ? 'desc' : 'asc';
                    $toggleParams = array_merge(request()->except(['sort_dir']), ['sort_dir' => $toggleDir]);
                    if (!$currentSort) {
                        $toggleParams = request()->all();
                    }
                @endphp
                <a href="{{ $currentSort ? route('shipped-items.index', $toggleParams) : '#' }}"
                   class="btn btn-sm {{ $currentSort ? 'btn-dark' : 'btn-outline-dark' }} sort-dir-toggle rounded-start-0 {{ !$currentSort ? 'disabled' : '' }}"
                   title="{{ $currentDir === 'asc' ? 'Ascending — click to switch to Descending' : 'Descending — click to switch to Ascending' }}">
                    <i class="bi bi-arrow-{{ $currentDir === 'asc' ? 'up' : 'down' }}"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Data Table --}}
<div class="card data-table-card">
    <div class="card-body p-0 table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Item Number</th>
                    <th>Weight</th>
                    <th>Destination</th>
                    <th>Retail Center</th>
                    <th>Delivery Date</th>
                    <th>Events</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td class="fw-semibold">{{ $item->item_number }}</td>
                    <td>{{ $item->weight }} kg</td>
                    <td>{{ Str::limit($item->destination, 30) }}</td>
                    <td>
                        <span class="badge bg-info bg-opacity-10 text-info badge-status">
                            {{ $item->retailCenter->unique_id ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        @if($item->final_delivery_date)
                            <span class="badge bg-success bg-opacity-10 text-success badge-status">
                                {{ \Carbon\Carbon::parse($item->final_delivery_date)->format('M d, Y') }}
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning badge-status">Pending</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary badge-status">
                            {{ $item->transportationEvents->count() }} event(s)
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('shipped-items.show', $item) }}" class="btn btn-sm btn-outline-info btn-action" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('shipped-items.edit', $item) }}" class="btn btn-sm btn-outline-primary btn-action" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if(auth()->user()->isAdmin())
                        <form action="{{ route('shipped-items.destroy', $item) }}" method="POST" class="d-inline-block btn-delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-box-seam"></i>
                            <h5>No Shipped Items Found</h5>
                            <p>Start by receiving a new package into the system.</p>
                            <a href="{{ route('shipped-items.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i>Receive Package
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="card-footer">
        {{ $items->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
