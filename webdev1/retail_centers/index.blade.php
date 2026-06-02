@extends('layouts.app')
@section('title', 'Retail Centers')

@php
    $currentSort = request('sort_by');
    $currentDir  = request('sort_dir', 'asc');

    $sortOptions = [
        'unique_id' => 'Unique ID',
        'type'      => 'Type',
        'address'   => 'Address',
    ];
@endphp

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h1><i class="bi bi-building me-2"></i>Retail Centers</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Retail Centers</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('retail-centers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Retail Center
    </a>
</div>

{{-- Search & Filter Bar --}}
<div class="card data-table-card mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex gap-2 align-items-center">
            {{-- Search --}}
            <form action="{{ route('retail-centers.index') }}" method="GET" class="d-flex gap-2 flex-grow-1">
                @if($currentSort)
                    <input type="hidden" name="sort_by" value="{{ $currentSort }}">
                    <input type="hidden" name="sort_dir" value="{{ $currentDir }}">
                @endif
                <div class="input-group input-group-sm flex-grow-1">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by ID, type, or address..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                @if(request('search'))
                    <a href="{{ route('retail-centers.index', $currentSort ? ['sort_by' => $currentSort, 'sort_dir' => $currentDir] : []) }}" class="btn btn-sm btn-outline-secondary">Clear</a>
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
                            <a href="{{ route('retail-centers.index', $isActive ? request()->except(['sort_by', 'sort_dir']) : $params) }}" class="sort-item {{ $isActive ? 'active' : '' }}">
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
                <a href="{{ $currentSort ? route('retail-centers.index', $toggleParams) : '#' }}"
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
                    <th>Unique ID</th>
                    <th>Type</th>
                    <th>Address</th>
                    <th>Packages</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centers as $center)
                <tr>
                    <td class="fw-semibold">{{ $center->unique_id }}</td>
                    <td><span class="badge bg-info bg-opacity-10 text-info badge-status">{{ $center->type }}</span></td>
                    <td>{{ Str::limit($center->address, 50) }}</td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary badge-status">{{ $center->shippedItems()->count() }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('retail-centers.show', $center) }}" class="btn btn-sm btn-outline-info btn-action"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('retail-centers.edit', $center) }}" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                        @if(auth()->user()->isAdmin())
                        <form action="{{ route('retail-centers.destroy', $center) }}" method="POST" class="d-inline-block btn-delete-confirm">
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
                            <i class="bi bi-building"></i>
                            <h5>No Retail Centers Found</h5>
                            <p>Add your first retail center to start receiving packages.</p>
                            <a href="{{ route('retail-centers.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Center</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($centers->hasPages())
    <div class="card-footer">{{ $centers->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
