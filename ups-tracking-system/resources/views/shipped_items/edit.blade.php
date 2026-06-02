@extends('layouts.app')
@section('title', 'Edit Package')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil-square me-2"></i>Edit Package</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shipped-items.index') }}">Shipped Items</a></li>
            <li class="breadcrumb-item active">Edit — {{ $shippedItem->item_number }}</li>
        </ol>
    </nav>
</div>

<div class="card form-card" style="max-width:720px;">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Update Package: {{ $shippedItem->item_number }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('shipped-items.update', $shippedItem) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- Item Number (Read-only) --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Item Number</label>
                    <input type="text" class="form-control" value="{{ $shippedItem->item_number }}" disabled>
                </div>

                {{-- Weight --}}
                <div class="col-md-6">
                    <label for="weight" class="form-label fw-semibold">Weight (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="weight" name="weight"
                           value="{{ old('weight', $shippedItem->weight) }}"
                           class="form-control @error('weight') is-invalid @enderror"
                           @error('weight') aria-invalid="true" aria-describedby="weight-error" @enderror required>
                    @error('weight') <div class="invalid-feedback" id="weight-error">{{ $message }}</div> @enderror
                </div>

                {{-- Dimensions --}}
                <div class="col-md-6">
                    <label for="dimensions" class="form-label fw-semibold">Dimensions <span class="text-danger">*</span></label>
                    <input type="text" id="dimensions" name="dimensions"
                           value="{{ old('dimensions', $shippedItem->dimensions) }}"
                           class="form-control @error('dimensions') is-invalid @enderror"
                           @error('dimensions') aria-invalid="true" aria-describedby="dimensions-error" @enderror required>
                    @error('dimensions') <div class="invalid-feedback" id="dimensions-error">{{ $message }}</div> @enderror
                </div>

                {{-- Insurance --}}
                <div class="col-md-6">
                    <label for="insurance_amount" class="form-label fw-semibold">Insurance Amount (₱) <span class="text-muted fw-normal">(Optional)</span></label>
                    <input type="number" step="0.01" id="insurance_amount" name="insurance_amount"
                           value="{{ old('insurance_amount', $shippedItem->insurance_amount) }}"
                           class="form-control @error('insurance_amount') is-invalid @enderror"
                           @error('insurance_amount') aria-invalid="true" aria-describedby="insurance_amount-error" @enderror>
                    @error('insurance_amount') <div class="invalid-feedback" id="insurance_amount-error">{{ $message }}</div> @enderror
                </div>

                {{-- Destination --}}
                <div class="col-12">
                    <label for="destination" class="form-label fw-semibold">Destination <span class="text-danger">*</span></label>
                    <input type="text" id="destination" name="destination"
                           value="{{ old('destination', $shippedItem->destination) }}"
                           class="form-control @error('destination') is-invalid @enderror"
                           @error('destination') aria-invalid="true" aria-describedby="destination-error" @enderror required>
                    @error('destination') <div class="invalid-feedback" id="destination-error">{{ $message }}</div> @enderror
                </div>

                {{-- Final Delivery Date --}}
                <div class="col-md-6">
                    <label for="final_delivery_date" class="form-label fw-semibold">Delivery Date</label>
                    <input type="date" id="final_delivery_date" name="final_delivery_date"
                           value="{{ old('final_delivery_date', $shippedItem->final_delivery_date) }}"
                           class="form-control @error('final_delivery_date') is-invalid @enderror"
                           @error('final_delivery_date') aria-invalid="true" aria-describedby="final_delivery_date-error" @enderror>
                    @error('final_delivery_date') <div class="invalid-feedback" id="final_delivery_date-error">{{ $message }}</div> @enderror
                </div>

                {{-- Retail Center --}}
                <div class="col-md-6">
                    <label for="retail_center_id" class="form-label fw-semibold">Retail Center <span class="text-danger">*</span></label>
                    <select id="retail_center_id" name="retail_center_id"
                            class="form-select @error('retail_center_id') is-invalid @enderror"
                            @error('retail_center_id') aria-invalid="true" aria-describedby="retail_center_id-error" @enderror required>
                        @foreach($retailCenters as $center)
                            <option value="{{ $center->id }}"
                                {{ old('retail_center_id', $shippedItem->retail_center_id) == $center->id ? 'selected' : '' }}>
                                {{ $center->unique_id }} — {{ $center->type }}
                            </option>
                        @endforeach
                    </select>
                    @error('retail_center_id') <div class="invalid-feedback" id="retail_center_id-error">{{ $message }}</div> @enderror
                </div>

                {{-- Transportation Events --}}
                <div class="col-12">
                    <label for="transportation_events" class="form-label fw-semibold">Transportation Events</label>
                    <select id="transportation_events" name="transportation_events[]"
                            class="form-select" multiple size="4">
                        @foreach($transportationEvents as $event)
                            <option value="{{ $event->id }}"
                                {{ in_array($event->id, old('transportation_events', $shippedItem->transportationEvents->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $event->schedule_number }} — {{ $event->type }} ({{ $event->delivery_route }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Hold Ctrl/Cmd to select multiple events.</div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('shipped-items.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Update Package
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
