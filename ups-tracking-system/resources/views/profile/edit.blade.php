@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-person me-2"></i>Profile Settings</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </nav>
</div>

<div class="row g-3" style="max-width:700px;">
    {{-- Update Profile Information --}}
    <div class="col-12">
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-person-lines-fill me-2"></i>Profile Information</h6></div>
            <div class="card-body">
                <p class="text-muted small mb-3">Update your account's profile information and email address.</p>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Name</label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required autofocus>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save</button>

                    @if (session('status') === 'profile-updated')
                        <span class="text-success small ms-2"><i class="bi bi-check-circle me-1"></i>Saved.</span>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Update Password --}}
    <div class="col-12">
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-lock me-2"></i>Update Password</h6></div>
            <div class="card-body">
                <p class="text-muted small mb-3">Ensure your account is using a long, random password to stay secure.</p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('put')

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">Current Password</label>
                        <input id="current_password" name="current_password" type="password"
                               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                        @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">New Password</label>
                        <input id="password" name="password" type="password"
                               class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                        @error('password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Password</button>

                    @if (session('status') === 'password-updated')
                        <span class="text-success small ms-2"><i class="bi bi-check-circle me-1"></i>Saved.</span>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Account --}}
    <div class="col-12">
        <div class="card form-card border-danger">
            <div class="card-header bg-danger bg-opacity-10"><h6 class="mb-0 fw-semibold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Delete Account</h6></div>
            <div class="card-body">
                <p class="text-muted small mb-3">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf @method('delete')

                    <div class="mb-3">
                        <label for="delete_password" class="form-label fw-semibold">Confirm Password</label>
                        <input id="delete_password" name="password" type="password"
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                               placeholder="Enter your password to confirm">
                        @error('password', 'userDeletion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.')">
                        <i class="bi bi-trash me-1"></i>Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
