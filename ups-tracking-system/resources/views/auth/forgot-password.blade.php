@extends('layouts.guest')
@section('title', 'Forgot Password')

@section('content')
    <p class="text-muted small mb-4">
        Forgot your password? No problem. Enter your email address and we'll send you a password reset link via Mailtrap.
    </p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="alert alert-success alert-sm" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required autofocus placeholder="you@example.com">
            </div>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send me-2"></i>Email Password Reset Link
            </button>
        </div>
    </form>
@endsection
