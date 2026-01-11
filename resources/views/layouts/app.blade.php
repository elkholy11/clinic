<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Clinic') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }
        [dir="rtl"] {
            text-align: right;
        }
        [dir="ltr"] {
            text-align: left;
        }
        .dropdown-menu {
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
        }

        /* Navbar Improvements */
        .navbar {
            transition: all 0.3s ease;
            border-bottom: 2px solid #e9ecef;
        }

        .navbar-brand {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem !important;
            border-radius: 5px;
            margin: 0 0.25rem;
        }

        .nav-link:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }

        .nav-link.active {
            background-color: #0d6efd;
            color: white !important;
            font-weight: 600;
        }

        .nav-link.active:hover {
            background-color: #0b5ed7;
            color: white !important;
        }

        .navbar-nav .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 0.5rem;
        }

        .navbar-nav .dropdown-item {
            transition: all 0.2s ease;
            padding: 0.5rem 1rem;
        }

        .navbar-nav .dropdown-item:hover {
            background-color: #f8f9fa;
            padding-left: 1.5rem;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        /* RTL/LTR Spacing Fixes */
        [dir="rtl"] .me-auto {
            margin-left: auto !important;
            margin-right: 0 !important;
        }
        [dir="ltr"] .me-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }
        [dir="rtl"] .ms-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }
        [dir="ltr"] .ms-auto {
            margin-left: auto !important;
            margin-right: 0 !important;
        }
        [dir="rtl"] .me-1 {
            margin-left: 0.25rem !important;
            margin-right: 0 !important;
        }
        [dir="ltr"] .me-1 {
            margin-right: 0.25rem !important;
            margin-left: 0 !important;
        }
        [dir="rtl"] .me-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }
        [dir="ltr"] .me-2 {
            margin-right: 0.5rem !important;
            margin-left: 0 !important;
        }
        [dir="rtl"] .ms-1 {
            margin-right: 0.25rem !important;
            margin-left: 0 !important;
        }
        [dir="ltr"] .ms-1 {
            margin-left: 0.25rem !important;
            margin-right: 0 !important;
        }
        [dir="rtl"] .ms-2 {
            margin-right: 0.5rem !important;
            margin-left: 0 !important;
        }
        [dir="ltr"] .ms-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }
        [dir="rtl"] .text-end {
            text-align: left !important;
        }
        [dir="ltr"] .text-end {
            text-align: right !important;
        }
        [dir="rtl"] .text-start {
            text-align: right !important;
        }
        [dir="ltr"] .text-start {
            text-align: left !important;
        }

        /* Ensure consistent padding for main content in RTL and LTR */
        main.py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        /* Force same padding regardless of direction */
        html[dir="rtl"] main.py-4,
        html[dir="ltr"] main.py-4,
        [dir="rtl"] main.py-4,
        [dir="ltr"] main.py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        /* Ensure container-fluid inside main has consistent spacing */
        main.py-4 .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
        
        html[dir="rtl"] main.py-4 .container-fluid,
        html[dir="ltr"] main.py-4 .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        /* Success Message */
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            animation: fadeOut 2s forwards;
            animation-delay: 1s;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
                visibility: hidden;
            }
        }

        /* Loading Spinner */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Improvements */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }

            .nav-link {
                padding: 0.5rem !important;
                margin: 0.1rem 0;
            }

            .stats-card .display-4 {
                font-size: 2rem;
            }

            .dashboard-header {
                padding: 1.5rem;
            }

            .dashboard-header h2 {
                font-size: 1.5rem;
            }
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Better Focus States */
        *:focus {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }

        button:focus,
        a:focus {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="spinner"></div>
        </div>

        @include('layouts.navigation')

        <main class="py-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Loading overlay for form submissions
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            const loadingOverlay = document.getElementById('loadingOverlay');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Only show loading for non-AJAX forms
                    if (!form.dataset.ajax) {
                        loadingOverlay.classList.add('active');
                    }
                });
            });

            // Hide loading overlay after page load
            window.addEventListener('load', function() {
                loadingOverlay.classList.remove('active');
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
