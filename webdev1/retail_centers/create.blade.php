@extends('layouts.app')
@section('title', 'Add Retail Center')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Add Retail Center</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('retail-centers.index') }}">Retail Centers</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<div class="card form-card" style="max-width:600px;">
    <div class="card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Center Details</h6></div>
    <div class="card-body">
        <form action="{{ route('retail-centers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="unique_id" class="form-label fw-semibold">Unique ID <span class="text-danger">*</span></label>
                <input type="text" id="unique_id" name="unique_id" value="{{ old('unique_id') }}"
                       class="form-control @error('unique_id') is-invalid @enderror" required placeholder="e.g., RC-MNL-001">
                @error('unique_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="">— Select Type —</option>
                    <option value="Hub" {{ old('type') == 'Hub' ? 'selected' : '' }}>Hub</option>
                    <option value="Drop-off" {{ old('type') == 'Drop-off' ? 'selected' : '' }}>Drop-off</option>
                    <option value="Service Center" {{ old('type') == 'Service Center' ? 'selected' : '' }}>Service Center</option>
                    <option value="Distribution Center" {{ old('type') == 'Distribution Center' ? 'selected' : '' }}>Distribution Center</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="address" class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                <textarea id="address" name="address" rows="3"
                          class="form-control @error('address') is-invalid @enderror" required placeholder="Full physical address">{{ old('address') }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('retail-centers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Center</button>
            </div>
        </form>
    </div>
</div>
@endsection
