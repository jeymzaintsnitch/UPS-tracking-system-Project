<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UPS Tracking') }} — @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <!-- Auth Header with Logo -->
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <h4>UPS Tracking System</h4>
                <p>Package Management Portal</p>
            </div>

            <!-- Auth Form Content -->
            <div class="auth-body">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
