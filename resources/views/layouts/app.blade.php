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
        .me-auto {
            margin-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: auto !important;
        }
        .ms-auto {
            margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: auto !important;
        }
        .me-1, .me-2 {
            margin-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 0.25rem !important;
        }
        .me-2 {
            margin-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 0.5rem !important;
        }
        .text-end {
            text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }} !important;
        }
        .text-start {
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }} !important;
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
    </style>
</head>
<body>
    <div id="app">
        @include('layouts.navigation')

        <main class="py-4">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
