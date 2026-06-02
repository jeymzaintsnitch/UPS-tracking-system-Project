@extends('layouts.guest')
@section('title', 'Verify Email')

@section('content')
    <div class="text-center mb-4">
        <i class="bi bi-envelope-check text-primary" style="font-size:3rem;"></i>
    </div>

    <p class="text-muted small mb-4 text-center">
        Thanks for signing up! Before getting started, please verify your email address by clicking the link we sent to your inbox.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success small" role="alert">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-arrow-repeat me-1"></i>Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">Log Out</button>
        </form>
    </div>
@endsection
