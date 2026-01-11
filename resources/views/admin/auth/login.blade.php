@extends('layouts.app')

@section('title', __('messages.admin_login'))

@push('styles')
<style>
    .login-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }
    
    .admin-login-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: slideUp 0.5s ease;
        max-width: 420px;
        width: 100%;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .admin-login-card .card-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 1.5rem;
        text-align: center;
        border: none;
    }
    
    .admin-login-card .card-header h3 {
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
    }
    
    .admin-login-card .card-body {
        padding: 1.5rem;
        background: white;
    }
    
    .form-control:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    .btn-admin-login {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
    }
    
    .btn-admin-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        color: white;
    }
    
    .input-group-icon {
        position: relative;
    }
    
    .input-group-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 10;
    }
    
    .input-group-icon input {
        padding-left: 45px;
    }
    
    [dir="rtl"] .input-group-icon i {
        left: auto;
        right: 15px;
    }
    
    [dir="rtl"] .input-group-icon input {
        padding-left: 15px;
        padding-right: 45px;
    }
    
    .admin-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card admin-login-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-shield me-2"></i>{{ __('messages.admin_login') }}</h3>
                        <span class="admin-badge"><i class="fas fa-shield-alt me-1"></i>{{ __('messages.administrator') }}</span>
                    </div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.login') }}" id="adminLoginForm">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('messages.email') }}</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('messages.email') }}">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('messages.password') }}</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-lock"></i>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('messages.password') }}">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('messages.remember_me') }}
                                    </label>
                                </div>
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    <i class="fas fa-key me-1"></i>{{ __('messages.forgot_password') }}
                                </a>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-admin-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.login') }}
                                </button>
                            </div>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                {{ __('messages.not_admin') }}? 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    <i class="fas fa-user me-1"></i>{{ __('messages.login') }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

