<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="UPS Package Tracking System — Real-time shipment tracking and logistics management.">
    <title>UPS Tracking System — Package Management Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    {{-- ═══ Hero Section ═══ --}}
    <div class="landing-hero d-flex align-items-center">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-5 mb-lg-0">
                    {{-- Brand --}}
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center justify-content-center rounded" style="width:50px;height:50px;background:#FFB500;color:#2A1610;font-size:1.5rem;">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <span class="fw-bold fs-5" style="letter-spacing:0.5px;">UPS TRACKING SYSTEM</span>
                    </div>

                    <h1 class="display-4 fw-bold mb-3" style="line-height:1.15;">
                        Precision Package <br>
                        <span style="color:#FFB500;">Tracking & Management</span>
                    </h1>

                    <p class="lead mb-4" style="color:rgba(255,255,255,0.7);max-width:520px;">
                        Company-wide information system for precise package processing, real-time location tracking, and complete logistics management.
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-lg px-4" style="background:#FFB500;color:#2A1610;font-weight:600;">
                                <i class="bi bi-grid-1x2-fill me-2"></i>Access Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-lg px-4" style="background:#FFB500;color:#2A1610;font-weight:600;">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Log In
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-lg btn-outline-light px-4">
                                    <i class="bi bi-person-plus me-2"></i>Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- Feature Cards --}}
                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card border-0 text-center p-3" style="background:rgba(255,255,255,0.08);border-radius:12px;">
                                <i class="bi bi-box-seam text-warning mb-2" style="font-size:2rem;"></i>
                                <h6 class="text-white mb-1">Shipped Items</h6>
                                <small style="color:rgba(255,255,255,0.5);">Full CRUD tracking</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-0 text-center p-3" style="background:rgba(255,255,255,0.08);border-radius:12px;">
                                <i class="bi bi-building text-warning mb-2" style="font-size:2rem;"></i>
                                <h6 class="text-white mb-1">Retail Centers</h6>
                                <small style="color:rgba(255,255,255,0.5);">Intake locations</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-0 text-center p-3" style="background:rgba(255,255,255,0.08);border-radius:12px;">
                                <i class="bi bi-truck text-warning mb-2" style="font-size:2rem;"></i>
                                <h6 class="text-white mb-1">Transport</h6>
                                <small style="color:rgba(255,255,255,0.5);">Flights & trucks</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-0 text-center p-3" style="background:rgba(255,255,255,0.08);border-radius:12px;">
                                <i class="bi bi-shield-lock text-warning mb-2" style="font-size:2rem;"></i>
                                <h6 class="text-white mb-1">Audit Trail</h6>
                                <small style="color:rgba(255,255,255,0.5);">Full system logs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ Features Section ═══ --}}
    <section class="py-5" style="background:#F0F2F5;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color:#351C15;">System Features</h2>
                <p class="text-muted">Everything you need to manage the UPS logistics pipeline</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center">
                        <i class="bi bi-people-fill mb-3" style="font-size:2.5rem;color:#351C15;"></i>
                        <h5 class="fw-bold" style="color:#351C15;">Role-Based Access</h5>
                        <p class="text-muted small mb-0">Admin and Staff roles with granular permissions. Staff can view and edit; only Admins can delete records and manage users.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center">
                        <i class="bi bi-envelope-check-fill mb-3" style="font-size:2.5rem;color:#351C15;"></i>
                        <h5 class="fw-bold" style="color:#351C15;">Password Recovery</h5>
                        <p class="text-muted small mb-0">Forgot your password? Reset it securely via email using Mailtrap SMTP integration.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center">
                        <i class="bi bi-journal-text mb-3" style="font-size:2.5rem;color:#351C15;"></i>
                        <h5 class="fw-bold" style="color:#351C15;">Audit Trail</h5>
                        <p class="text-muted small mb-0">Logged every action with timestamps, user info, and old/new values.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ Footer ═══ --}}
    <footer class="py-4 text-center" style="background:#351C15;color:rgba(255,255,255,0.5);">
        <div class="container">
            <small>&copy; {{ date('Y') }} UPS Tracking System. Built with Laravel, Blade & Bootstrap 5.</small>
        </div>
    </footer>
</body>
</html>