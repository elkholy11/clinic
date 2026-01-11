@extends('layouts.app')

@section('title', __('messages.profile'))

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
    }
    
    .profile-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .profile-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        margin: 0 auto 1.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .info-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .info-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    
    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .form-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .danger-zone {
        border: 2px solid #dc3545;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .danger-zone .card-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
    }
    
    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="profile-header">
        <div class="text-center">
            <h2 class="mb-2"><i class="fas fa-user-circle me-2"></i>{{ __('messages.my_profile') }}</h2>
            <p class="mb-0 opacity-75">{{ __('messages.edit_profile') }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card profile-card">
                <div class="card-body text-center">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <h4 class="card-title mb-2">{{ auth()->user()->name }}</h4>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->isAdmin())
                        <span class="badge bg-danger mb-2">
                            <i class="fas fa-user-shield me-1"></i>{{ __('messages.administrator') }}
                        </span>
                    @elseif(auth()->user()->isDoctor())
                        <span class="badge bg-success mb-2">
                            <i class="fas fa-user-md me-1"></i>{{ __('messages.doctor') }}
                        </span>
                        @if(auth()->user()->doctor)
                            <p class="text-muted mb-0">
                                <small><i class="fas fa-stethoscope me-1"></i>{{ auth()->user()->doctor->specialty }}</small>
                            </p>
                        @endif
                    @else
                        <span class="badge bg-info mb-2">
                            <i class="fas fa-user me-1"></i>{{ __('messages.user') }}
                        </span>
                    @endif
                </div>
            </div>

            @if(auth()->user()->isDoctor() && auth()->user()->doctor)
            <div class="card info-card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>{{ __('messages.doctor_information') }}</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>{{ __('messages.name') }}:</strong> {{ auth()->user()->doctor->name }}</p>
                    <p class="mb-2"><strong>{{ __('messages.specialty') }}:</strong> {{ auth()->user()->doctor->specialty }}</p>
                    <p class="mb-2"><strong>{{ __('messages.phone') }}:</strong> {{ auth()->user()->doctor->phone }}</p>
                    @if(auth()->user()->doctor->email)
                        <p class="mb-0"><strong>{{ __('messages.email') }}:</strong> {{ auth()->user()->doctor->email }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card form-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>{{ __('messages.edit_profile') }}</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('Patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', auth()->user()->name) }}" required autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('messages.email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', auth()->user()->email) }}" required autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3"><i class="fas fa-key me-2"></i>{{ __('messages.change_password') }}</h6>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('messages.current_password') }}</label>
                            <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" autocomplete="current-password">
                            @error('current_password')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">{{ __('messages.leave_blank_to_keep_current_password') }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('messages.new_password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }}</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="submit" class="btn btn-update">
                                <i class="fas fa-save me-2"></i>{{ __('messages.update_profile') }}
                            </button>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>{{ __('messages.last_updated') }}: {{ auth()->user()->updated_at->diffForHumans() }}
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card danger-zone mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.danger_zone') }}</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">{{ __('messages.delete_account_warning') }}</p>
                    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('{{ __('messages.are_you_sure_delete_account') }}')">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>{{ __('messages.delete_account') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
