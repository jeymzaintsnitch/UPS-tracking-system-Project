@extends('layouts.app')
@section('title', 'Edit Retail Center')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil-square me-2"></i>Edit Retail Center</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('retail-centers.index') }}">Retail Centers</a></li>
            <li class="breadcrumb-item active">Edit — {{ $retailCenter->unique_id }}</li>
        </ol>
    </nav>
</div>

<div class="card form-card" style="max-width:600px;">
    <div class="card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Update: {{ $retailCenter->unique_id }}</h6></div>
    <div class="card-body">
        <form action="{{ route('retail-centers.update', $retailCenter) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Unique ID</label>
                <input type="text" class="form-control" value="{{ $retailCenter->unique_id }}" disabled>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="Hub" {{ old('type', $retailCenter->type) == 'Hub' ? 'selected' : '' }}>Hub</option>
                    <option value="Drop-off" {{ old('type', $retailCenter->type) == 'Drop-off' ? 'selected' : '' }}>Drop-off</option>
                    <option value="Service Center" {{ old('type', $retailCenter->type) == 'Service Center' ? 'selected' : '' }}>Service Center</option>
                    <option value="Distribution Center" {{ old('type', $retailCenter->type) == 'Distribution Center' ? 'selected' : '' }}>Distribution Center</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="address" class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                <textarea id="address" name="address" rows="3"
                          class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $retailCenter->address) }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('retail-centers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Center</button>
            </div>
        </form>
    </div>
</div>
@endsection
