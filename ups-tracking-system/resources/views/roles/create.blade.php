@extends('layouts.app')
@section('title', 'Roles / Create')

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h1>Roles / Create</h1>
    </div>
</div>

<div class="card border-0 shadow-sm mt-3" style="border-radius: 8px;">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="form-label text-muted fs-6 mb-2">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required style="max-width: 400px; border-radius: 6px;" placeholder="e.g. staff">
            </div>

            <div class="row g-3 mb-4">
                @foreach($permissions as $permission)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                        <label class="form-check-label text-dark" style="font-weight: 500;" for="perm_{{ $permission->id }}">
                            {{ str_replace('_', ' ', $permission->name) }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-dark px-4 py-2" style="border-radius: 6px; font-weight: 500;">Create Role</button>
            </div>
        </form>
    </div>
</div>
@endsection
