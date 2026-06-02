<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="UPS Package Tracking System — Real-time shipment tracking and logistics management.">

    <title>{{ config('app.name', 'UPS Tracking') }} — @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1035;"></div>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- Sidebar Navigation                                                 -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <aside id="sidebar" class="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div class="brand-text">
                UPS Tracker
                <small>Package Management</small>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="nav-section-title">Main Menu</div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
        </ul>

        <!-- Entity Management -->
        <div class="nav-section-title">Maintenance</div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="{{ route('shipped-items.index') }}" class="nav-link {{ request()->routeIs('shipped-items.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Shipped Items
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('retail-centers.index') }}" class="nav-link {{ request()->routeIs('retail-centers.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Retail Centers
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transportation-events.index') }}" class="nav-link {{ request()->routeIs('transportation-events.*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i> Transport Events
                </a>
            </li>
        </ul>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:rgba(255,255,255,0.15);color:#fff;font-size:0.8rem;font-weight:600;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-grow-1" style="min-width:0;">
                    <div style="font-size:0.8rem;font-weight:500;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ Auth::user()->name }}
                    </div>
                    <div style="font-size:0.68rem;color:rgba(255,255,255,0.5);">
                        {{ Auth::user()->roles->first()?->name ?? 'User' }}
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- Main Content Area                                                  -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <!-- Sidebar Toggle -->
                <button id="sidebarToggle" class="btn btn-sm btn-dark btn-sidebar-toggle border-2" aria-label="Toggle sidebar" aria-expanded="true" aria-controls="sidebar">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>
                <h6 class="mb-0 fw-semibold text-muted d-none d-md-block">@yield('title', 'Dashboard')</h6>
                
                @if(auth()->user()->isAdmin())
                <div class="d-flex align-items-center gap-2 ms-md-3 border-start ps-md-3">
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-dark fw-medium {{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="bi bi-people-fill me-1"></i>User Management</a>
                    <a href="{{ route('audit-logs.index') }}" class="btn btn-sm btn-outline-dark fw-medium {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}"><i class="bi bi-shield-lock-fill me-1"></i>Audit Trail</a>
                </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile Settings</a></li>
                        @if(auth()->user()->isAdmin())
                            <li><a class="dropdown-item" href="{{ route('authentication-logs.index') }}"><i class="bi bi-journal-text me-2"></i>Auth Logs</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="content-wrapper">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" aria-live="polite">
                    <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="assertive">
                    <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="assertive">
                    <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
                    <strong>Validation Error:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
