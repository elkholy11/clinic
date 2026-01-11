@extends('layouts.app')

@section('title', __('messages.user_details'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(48, 207, 208, 0.15);
    }
    
    .page-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.75rem;
    }
    
    .detail-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .detail-card .card-title {
        color: #30cfd0;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .detail-card table {
        margin-bottom: 0;
    }
    
    .detail-card table.table-borderless {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .detail-card table.table-borderless tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }
    
    .detail-card table.table-borderless tr:last-child {
        border-bottom: none;
    }
    
    .detail-card table.table-borderless tr:hover {
        background-color: rgba(48, 207, 208, 0.03);
    }
    
    .detail-card table th {
        width: 40%;
        color: #6c757d;
        font-weight: 500;
    }
    
    .detail-card table td {
        font-weight: 400;
    }
    
    .actions-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-user me-2"></i>{{ __('messages.user_details') }}</h2>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card detail-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>{{ __('messages.user_information') }}
                    </h5>
                    <table class="table table-borderless">
                        <tr>
                            <th><i class="fas fa-user me-2 text-muted"></i>{{ __('messages.name') }}:</th>
                            <td><strong>{{ $user->name }}</strong></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope me-2 text-muted"></i>{{ __('messages.email') }}:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-user-tag me-2 text-muted"></i>{{ __('messages.role') }}:</th>
                            <td>
                                @if($user->isDoctor())
                                    <span class="badge bg-success">
                                        <i class="fas fa-user-md me-1"></i>{{ __('messages.doctor') }}
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="fas fa-user-shield me-1"></i>{{ __('messages.administrator') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @if($user->doctor)
                        <tr>
                            <th><i class="fas fa-user-md me-2 text-muted"></i>{{ __('messages.linked_doctor') }}:</th>
                            <td>
                                <a href="{{ route('admin.doctors.show', $user->doctor) }}" class="text-decoration-none">
                                    <i class="fas fa-user-md me-1"></i>{{ $user->doctor->name }}
                                </a>
                                <br>
                                <small class="text-muted">{{ $user->doctor->specialty }}</small>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th><i class="fas fa-calendar-plus me-2 text-muted"></i>{{ __('messages.created_at') }}:</th>
                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar-edit me-2 text-muted"></i>{{ __('messages.last_updated') }}:</th>
                            <td>{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card detail-card">
                <div class="card-body actions-card">
                    <h5 class="card-title">
                        <i class="fas fa-cog me-2"></i>{{ __('messages.actions') }}
                    </h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>{{ __('messages.edit_user') }}
                        </a>
                        @if($user->id !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('{{ __('messages.are_you_sure') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i>{{ __('messages.delete_user') }}
                                </button>
                            </form>
                        @else
                            <button class="btn btn-danger w-100" disabled>
                                <i class="fas fa-trash me-2"></i>{{ __('messages.cannot_delete_yourself') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
