@extends('layouts.app')
@section('title', 'Schedule Transportation Event')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Schedule Transportation Event</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('transportation-events.index') }}">Transportation Events</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<div class="card form-card" style="max-width:600px;">
    <div class="card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Event Details</h6></div>
    <div class="card-body">
        <form action="{{ route('transportation-events.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="schedule_number" class="form-label fw-semibold">Schedule Number <span class="text-danger">*</span></label>
                <input type="text" id="schedule_number" name="schedule_number" value="{{ old('schedule_number') }}"
                       class="form-control @error('schedule_number') is-invalid @enderror" required placeholder="e.g., FLT-2026-0042">
                @error('schedule_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="">— Select Type —</option>
                    <option value="Flight" {{ old('type') == 'Flight' ? 'selected' : '' }}>✈ Flight</option>
                    <option value="Truck" {{ old('type') == 'Truck' ? 'selected' : '' }}>🚛 Truck</option>
                    <option value="Ship" {{ old('type') == 'Ship' ? 'selected' : '' }}>🚢 Ship</option>
                    <option value="Rail" {{ old('type') == 'Rail' ? 'selected' : '' }}>🚂 Rail</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="delivery_route" class="form-label fw-semibold">Delivery Route <span class="text-danger">*</span></label>
                <input type="text" id="delivery_route" name="delivery_route" value="{{ old('delivery_route') }}"
                       class="form-control @error('delivery_route') is-invalid @enderror" required placeholder="e.g., Manila → Cebu → Davao">
                @error('delivery_route') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('transportation-events.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Event</button>
            </div>
        </form>
    </div>
</div>
@endsection
