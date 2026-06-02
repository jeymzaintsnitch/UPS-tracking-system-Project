@extends('layouts.app')
@section('title', 'Roles & Permissions')

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h1><i class="bi bi-shield-lock-fill me-2"></i>Roles & Permissions</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Create Role
    </a>
</div>

<div class="card data-table-card">
    <div class="card-body p-0 table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Role Name</th>
                    <th>Permissions Count</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>
                        <span class="badge {{ $role->name === 'Admin' ? 'bg-danger bg-opacity-10 text-danger' : 'bg-primary bg-opacity-10 text-primary' }} badge-status fs-6">
                            {{ $role->name }}
                        </span>
                    </td>
                    <td>
                        <span class="text-muted">{{ $role->permissions_count }} permissions</span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3"><div class="empty-state"><i class="bi bi-shield-x"></i><h5>No Roles Found</h5></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
