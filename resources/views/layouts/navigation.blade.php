<style>
    .navbar .dropdown-menu {
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        border-radius: 10px;
        margin-top: 0.5rem;
        padding: 0.75rem 0;
        min-width: 220px;
        animation: fadeInDown 0.3s ease;
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .navbar .dropdown-item {
        padding: 0.75rem 1.5rem;
        transition: all 0.25s ease;
        border-radius: 6px;
        margin: 0.15rem 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .navbar .dropdown-item i {
        width: 20px;
        text-align: center;
    }
    
    .navbar .dropdown-item:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateX(8px);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    
    .navbar .dropdown-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
    }
    
    .navbar .nav-link.dropdown-toggle {
        position: relative;
    }
    
    .navbar .nav-link.dropdown-toggle::after {
        margin-left: 0.5rem;
        transition: transform 0.3s ease;
    }
    
    .navbar .nav-link.dropdown-toggle[aria-expanded="true"]::after {
        transform: rotate(180deg);
    }
    
    .navbar .nav-link {
        transition: all 0.25s ease;
        border-radius: 8px;
        margin: 0 0.25rem;
        padding: 0.5rem 1rem !important;
        font-weight: 500;
    }
    
    .navbar .nav-link:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        transform: translateY(-2px);
    }
    
    .navbar .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .navbar .nav-link.active i {
        color: white;
    }
    
    @media (max-width: 991px) {
        .navbar .dropdown-menu {
            box-shadow: none;
            border: 1px solid #e9ecef;
            margin-left: 1rem;
            border-radius: 8px;
        }
        
        .navbar .dropdown-item:hover {
            transform: none;
        }
    }
</style>

<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <i class="fas fa-hospital me-2 text-primary"></i>{{ config('app.name', 'Clinic') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('messages.toggle_navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            @auth
            <ul class="navbar-nav me-auto">
                @php
                    $user = Auth::user();
                    $role = \App\Helpers\UserHelper::getUserRole($user);
                    $isAdmin = $role['isAdmin'];
                    $isDoctor = $role['isDoctor'];
                @endphp
                
                @if($isAdmin)
                    {{-- Admin Navigation - Uses admin.* routes --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> {{ __('messages.dashboard') }}
                        </a>
                    </li>
                    
                    {{-- Management Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.doctors.*') || request()->routeIs('admin.patients.*') || request()->routeIs('admin.users.*') ? 'active' : '' }}" href="#" id="managementDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-users-cog me-1"></i> {{ __('messages.management') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="managementDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}" href="{{ route('admin.doctors.index') }}">
                                    <i class="fas fa-user-md me-2"></i> {{ __('messages.doctors') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}" href="{{ route('admin.patients.index') }}">
                                    <i class="fas fa-users me-2"></i> {{ __('messages.patients') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-users-cog me-2"></i> {{ __('messages.users') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    {{-- Medical Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.appointments.*') || request()->routeIs('admin.reports.*') || request()->routeIs('admin.prescriptions.*') || request()->routeIs('admin.medical-history.*') ? 'active' : '' }}" href="#" id="medicalDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-stethoscope me-1"></i> {{ __('messages.medical') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="medicalDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}" href="{{ route('admin.appointments.index') }}">
                                    <i class="fas fa-calendar-check me-2"></i> {{ __('messages.appointments') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                                    <i class="fas fa-file-medical me-2"></i> {{ __('messages.reports') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.prescriptions.*') ? 'active' : '' }}" href="{{ route('admin.prescriptions.index') }}">
                                    <i class="fas fa-prescription me-2"></i> {{ __('messages.prescriptions') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.medical-history.*') ? 'active' : '' }}" href="{{ route('admin.medical-history.index') }}">
                                    <i class="fas fa-history me-2"></i> {{ __('messages.medical_history') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    {{-- Pharmacy Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.medications.*') ? 'active' : '' }}" href="#" id="pharmacyDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-pills me-1"></i> {{ __('messages.pharmacy') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="pharmacyDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.medications.*') ? 'active' : '' }}" href="{{ route('admin.medications.index') }}">
                                    <i class="fas fa-pills me-2"></i> {{ __('messages.medications') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    {{-- Financial Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.invoices.*') || request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="#" id="financialDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-dollar-sign me-1"></i> {{ __('messages.financial') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="financialDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}" href="{{ route('admin.invoices.index') }}">
                                    <i class="fas fa-file-invoice me-2"></i> {{ __('messages.invoices') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                                    <i class="fas fa-money-bill-wave me-2"></i> {{ __('messages.payments') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif($isDoctor)
                    {{-- Doctor Navigation - Uses admin.* routes (doctors share admin routes) --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> {{ __('messages.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}" href="{{ route('admin.appointments.index') }}">
                            <i class="fas fa-calendar-check me-1"></i> {{ __('messages.appointments') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                            <i class="fas fa-file-medical me-1"></i> {{ __('messages.reports') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.prescriptions.*') ? 'active' : '' }}" href="{{ route('admin.prescriptions.index') }}">
                            <i class="fas fa-prescription me-1"></i> {{ __('messages.prescriptions') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.medical-history.*') ? 'active' : '' }}" href="{{ route('admin.medical-history.index') }}">
                            <i class="fas fa-history me-1"></i> {{ __('messages.medical_history') }}
                        </a>
                    </li>
                @else
                    {{-- Regular User Navigation - MUST use user.* routes ONLY --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> {{ __('messages.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.appointments.*') ? 'active' : '' }}" href="{{ route('user.appointments.index') }}">
                            <i class="fas fa-calendar-check me-1"></i> {{ __('messages.appointments') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.reports.*') ? 'active' : '' }}" href="{{ route('user.reports.index') }}">
                            <i class="fas fa-file-medical me-1"></i> {{ __('messages.reports') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.prescriptions.*') ? 'active' : '' }}" href="{{ route('user.prescriptions.index') }}">
                            <i class="fas fa-prescription me-1"></i> {{ __('messages.prescriptions') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.medical-history.*') ? 'active' : '' }}" href="{{ route('user.medical-history.index') }}">
                            <i class="fas fa-history me-1"></i> {{ __('messages.medical_history') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.medications.*') ? 'active' : '' }}" href="{{ route('user.medications.index') }}">
                            <i class="fas fa-pills me-1"></i> {{ __('messages.medications') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.invoices.*') ? 'active' : '' }}" href="{{ route('user.invoices.index') }}">
                            <i class="fas fa-file-invoice me-1"></i> {{ __('messages.invoices') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.payments.*') ? 'active' : '' }}" href="{{ route('user.payments.index') }}">
                            <i class="fas fa-money-bill-wave me-1"></i> {{ __('messages.payments') }}
                        </a>
                    </li>
                @endif
            </ul>
            @endauth

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Language Switcher -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ app()->getLocale() === 'ar' ? 'ar' : 'En' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('language.switch', ['locale' => 'en']) }}">
                                English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('language.switch', ['locale' => 'ar']) }}">
                                العربية
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>{{ __('messages.login') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>{{ __('messages.register') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.login') }}">
                            <i class="fas fa-user-shield me-1"></i>{{ __('messages.admin_login') }}
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        @php
                            $user = Auth::user();
                            $role = \App\Helpers\UserHelper::getUserRole($user);
                            $isAdmin = $role['isAdmin'];
                            $isDoctor = $role['isDoctor'];
                        @endphp
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fas fa-user-circle me-1"></i>{{ $user->name }}
                            @if($isAdmin)
                                <span class="badge bg-danger ms-1">{{ __('messages.administrator') }}</span>
                            @elseif($isDoctor)
                                <span class="badge bg-success ms-1">{{ __('messages.doctor') }}</span>
                            @else
                                <span class="badge bg-info ms-1">{{ __('messages.user') }}</span>
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <div class="px-3 py-2 border-bottom">
                                <small class="text-muted d-block">{{ __('messages.logged_in_as') }}</small>
                                <strong>{{ $user->name }}</strong>
                                @if($isAdmin)
                                    <span class="badge bg-danger ms-1">{{ __('messages.administrator') }}</span>
                                @elseif($isDoctor)
                                    <span class="badge bg-success ms-1">{{ __('messages.doctor') }}</span>
                                @else
                                    <span class="badge bg-info ms-1">{{ __('messages.user') }}</span>
                                @endif
                            </div>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-cog me-1"></i> {{ __('messages.profile') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ $isAdmin ? route('admin.logout') : route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-1"></i> {{ __('messages.logout') }}
                            </a>

                            <form id="logout-form" action="{{ $isAdmin ? route('admin.logout') : route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
