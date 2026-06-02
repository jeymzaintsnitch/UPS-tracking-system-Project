<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="UPS Package Tracking System — Real-time shipment tracking and logistics management.">

    <title>{{ config('app.name', 'UPS Tracking') }} — Dashboard</title>

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
    <!-- (1) Fixed Left Sidebar                                             -->
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
                <a href="{{ route('dashboard') }}" class="nav-link active">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
        </ul>

        <!-- Entity Management -->
        <div class="nav-section-title">Maintenance</div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="{{ route('shipped-items.index') }}" class="nav-link">
                    <i class="bi bi-box-seam"></i> Shipped Items
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('retail-centers.index') }}" class="nav-link">
                    <i class="bi bi-building"></i> Retail Centers
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transportation-events.index') }}" class="nav-link">
                    <i class="bi bi-truck"></i> Transport Events
                </a>
            </li>
        </ul>

        <!-- Sidebar Footer -->
        @auth
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
        @endauth
    </aside>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- Main Content Area                                                  -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div class="main-content">
        <!-- (2) Top Navbar -->
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <!-- Sidebar Toggle -->
                <button id="sidebarToggle" class="btn btn-sm btn-dark btn-sidebar-toggle border-2" aria-label="Toggle sidebar" aria-expanded="true" aria-controls="sidebar">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>
                <h6 class="mb-0 fw-semibold text-muted d-none d-md-block">Dashboard</h6>
                
                @if(auth()->user() && auth()->user()->isAdmin())
                <div class="d-flex align-items-center gap-2 ms-md-3 border-start ps-md-3">
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-dark fw-medium"><i class="bi bi-people-fill me-1"></i>User Management</a>
                    <a href="{{ route('audit-logs.index') }}" class="btn btn-sm btn-outline-dark fw-medium"><i class="bi bi-shield-lock-fill me-1"></i>Audit Trail</a>
                </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- User Dropdown -->
                @auth
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
                @endauth
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

            <div class="page-header d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="text-primary"><i class="bi bi-grid-1x2-fill me-2"></i>UPS Tracker</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Overview</li>
                        </ol>
                    </nav>
                </div>
                <span class="badge bg-light text-dark border">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('F d, Y') }}
                </span>
            </div>

            {{-- ═══ Statistics Cards ═══ --}}
            <div class="row g-3 mb-4">
                {{-- Total Shipped Items --}}
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('shipped-items.index') }}" class="card stat-card fade-in-up text-decoration-none text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stat-label">Total Packages</div>
                                    <div class="stat-value text-primary">{{ number_format($stats['total_items'] ?? 0) }}</div>
                                </div>
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="stat-change text-success"><i class="bi bi-check-circle me-1"></i>{{ $stats['delivered_items'] ?? 0 }} delivered</span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Pending Deliveries --}}
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('shipped-items.index', ['status' => 'pending']) }}" class="card stat-card fade-in-up text-decoration-none text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stat-label">Pending Delivery</div>
                                    <div class="stat-value text-warning">{{ number_format($stats['pending_items'] ?? 0) }}</div>
                                </div>
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="stat-change text-muted"><i class="bi bi-arrow-right me-1"></i>In transit</span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Retail Centers --}}
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('retail-centers.index') }}" class="card stat-card fade-in-up text-decoration-none text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stat-label">Retail Centers</div>
                                    <div class="stat-value text-info">{{ number_format($stats['total_centers'] ?? 0) }}</div>
                                </div>
                                <div class="stat-icon bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-building"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="stat-change text-muted"><i class="bi bi-geo-alt me-1"></i>Active locations</span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Transport Events --}}
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('transportation-events.index') }}" class="card stat-card fade-in-up text-decoration-none text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stat-label">Transport Events</div>
                                    <div class="stat-value text-success">{{ number_format($stats['total_events'] ?? 0) }}</div>
                                </div>
                                <div class="stat-icon bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-truck"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="stat-change text-muted"><i class="bi bi-arrow-left-right me-1"></i>Routes active</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row g-3">
                {{-- ═══ Recent Packages ═══ --}}
                <div class="col-lg-7">
                    <div class="card data-table-card fade-in-up">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-box-seam me-2"></i>Recent Packages</h6>
                            <a href="{{ route('shipped-items.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Item #</th>
                                        <th>Destination</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentItems ?? [] as $item)
                                    <tr>
                                        <td class="fw-semibold">{{ $item->item_number }}</td>
                                        <td>{{ Str::limit($item->destination, 30) }}</td>
                                        <td>
                                            @if($item->final_delivery_date)
                                                <span class="badge bg-success bg-opacity-10 text-success badge-status">Delivered</span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning badge-status">In Transit</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $item->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No packages yet</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ═══ Recent Activity (Audit Log) ═══ --}}
                <div class="col-lg-5">
                    <div class="card data-table-card fade-in-up">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-activity me-2"></i>Recent Activity</h6>
                            @if(auth()->user() && auth()->user()->isAdmin())
                                <a href="{{ route('audit-logs.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                            @endif
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($recentLogs ?? [] as $log)
                                <div class="list-group-item px-3 py-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="badge
                                                @if($log->action === 'CREATE') bg-success bg-opacity-10 text-success
                                                @elseif($log->action === 'UPDATE') bg-info bg-opacity-10 text-info
                                                @else bg-danger bg-opacity-10 text-danger
                                                @endif badge-status me-1">{{ $log->action }}</span>
                                            <span class="small fw-medium">{{ $log->entity }}</span>
                                        </div>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        by {{ $log->user->name ?? 'System' }}
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-journal-text" style="font-size:2rem;"></i>
                                    <p class="mt-2 mb-0 small">No activity recorded yet</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
