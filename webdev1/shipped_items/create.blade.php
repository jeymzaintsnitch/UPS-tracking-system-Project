@extends('layouts.app')
@section('title', 'Receive New Package')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Receive New Package</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shipped-items.index') }}">Shipped Items</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<div class="card form-card" style="max-width:720px;">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Package Details</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('shipped-items.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                {{-- Item Number --}}
                <div class="col-md-6">
                    <label for="item_number" class="form-label fw-semibold">Item Number <span class="text-danger">*</span></label>
                    <input type="text" id="item_number" name="item_number" value="{{ old('item_number') }}"
                           class="form-control @error('item_number') is-invalid @enderror" required placeholder="e.g., 1Z999AA10123456784">
                    @error('item_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Weight --}}
                <div class="col-md-6">
                    <label for="weight" class="form-label fw-semibold">Weight (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="weight" name="weight" value="{{ old('weight') }}"
                           class="form-control @error('weight') is-invalid @enderror" required placeholder="e.g., 2.50">
                    @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Dimensions --}}
                <div class="col-md-6">
                    <label for="dimensions" class="form-label fw-semibold">Dimensions <span class="text-danger">*</span></label>
                    <input type="text" id="dimensions" name="dimensions" value="{{ old('dimensions') }}"
                           class="form-control @error('dimensions') is-invalid @enderror" required placeholder="e.g., 30x20x15 cm">
                    @error('dimensions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Insurance Amount --}}
                <div class="col-md-6">
                    <label for="insurance_amount" class="form-label fw-semibold">Insurance Amount (₱) <span class="text-muted fw-normal"><strong>(Optional)</strong></span></label>
                    <input type="number" step="0.01" id="insurance_amount" name="insurance_amount" value="{{ old('insurance_amount', '0') }}"
                           class="form-control @error('insurance_amount') is-invalid @enderror">
                    @error('insurance_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Destination --}}
                <div class="col-12">
                    <label for="destination" class="form-label fw-semibold">Destination <span class="text-danger">*</span></label>
                    <input type="text" id="destination" name="destination" value="{{ old('destination') }}"
                           class="form-control @error('destination') is-invalid @enderror" required placeholder="e.g., Taguig Delivery Hub, Metro Manila">
                    @error('destination') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Final Delivery Date --}}
                <div class="col-md-6">
                    <label for="final_delivery_date" class="form-label fw-semibold">Estimated Delivery Date</label>
                    <input type="date" id="final_delivery_date" name="final_delivery_date" value="{{ old('final_delivery_date') }}"
                           class="form-control @error('final_delivery_date') is-invalid @enderror">
                    @error('final_delivery_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Retail Center --}}
                <div class="col-md-6">
                    <label for="retail_center_id" class="form-label fw-semibold">Receiving Retail Center <span class="text-danger">*</span></label>
                    <select id="retail_center_id" name="retail_center_id"
                            class="form-select @error('retail_center_id') is-invalid @enderror" required>
                        <option value="">— Select Center —</option>
                        @foreach($retailCenters as $center)
                            <option value="{{ $center->id }}" {{ old('retail_center_id') == $center->id ? 'selected' : '' }}>
                                {{ $center->unique_id }} — {{ $center->type }}
                            </option>
                        @endforeach
                    </select>
                    @error('retail_center_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Transportation Events (Multi-Select) --}}
                <div class="col-12">
                    <label for="transportation_events" class="form-label fw-semibold">Transportation Events</label>
                    <select id="transportation_events" name="transportation_events[]"
                            class="form-select @error('transportation_events') is-invalid @enderror" multiple size="4">
                        @foreach($transportationEvents as $event)
                            <option value="{{ $event->id }}" {{ in_array($event->id, old('transportation_events', [])) ? 'selected' : '' }}>
                                {{ $event->schedule_number }} — {{ $event->type }} ({{ $event->delivery_route }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Hold Ctrl/Cmd to select multiple events.</div>
                    @error('transportation_events') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('shipped-items.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Save Package
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
