@extends('layouts.app')

@section('title', __('messages.doctors'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
    }
    
    .page-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.75rem;
    }
    
    .table-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        background: white;
    }
    
    .table-card .card-body {
        padding: 0;
    }
    
    .table {
        margin-bottom: 0;
        background: white;
    }
    
    .table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .table thead th {
        border: none;
        padding: 1.25rem 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.8px;
        white-space: nowrap;
    }
    
    .table tbody tr {
        transition: all 0.25s ease;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .table tbody tr:last-child {
        border-bottom: none;
    }
    
    .table tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
        transform: translateX(3px);
        box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }
    
    .table tbody td {
        padding: 1.5rem 1rem;
        vertical-align: middle;
        border-top: none;
        color: #495057;
        font-size: 0.95rem;
    }
    
    .table tbody td:first-child {
        font-weight: 600;
        color: #212529;
    }
    
    .table tbody td:nth-child(4) {
        color: #667eea;
        font-weight: 500;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.4rem;
        align-items: center;
        justify-content: flex-start;
    }
    
    .action-buttons {
        flex-wrap: nowrap;
    }
    
    .action-buttons .btn {
        width: 38px;
        height: 38px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: none;
        font-size: 0.9rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    
    .action-buttons .btn:hover {
        transform: translateY(-3px) scale(1.08);
        box-shadow: 0 6px 15px rgba(0,0,0,0.25);
    }
    
    .action-buttons .btn:active {
        transform: translateY(-1px) scale(1.03);
    }
    
    .action-buttons .btn-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    
    .action-buttons .btn-info:hover {
        background: linear-gradient(135deg, #3d9bfe 0%, #00e0fe 100%);
        color: white;
    }
    
    .action-buttons .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .action-buttons .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        color: white;
    }
    
    .action-buttons .btn-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .action-buttons .btn-danger:hover {
        background: linear-gradient(135deg, #e882f0 0%, #e4465b 100%);
        color: white;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-add:active {
        transform: translateY(-1px);
    }
    
    /* Empty state */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.02) 0%, rgba(118, 75, 162, 0.02) 100%);
    }
    
    .empty-state i {
        opacity: 0.2;
        margin-bottom: 1.5rem;
        color: #667eea;
    }
    
    .empty-state h5 {
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    
    /* Pagination */
    .card-footer {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 1.25rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.25rem 1.5rem;
        }
        
        .page-header h2 {
            font-size: 1.5rem;
        }
        
        .table thead th,
        .table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }
        
        .action-buttons .btn {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-user-md me-2"></i>{{ __('messages.doctors') }}</h2>
            </div>
            <div>
                @if(Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('admin.doctors.create') }}" class="btn btn-add">
                    <i class="fas fa-plus me-2"></i>{{ __('messages.add_doctor') }}
                </a>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            @if(session('user_password'))
                <hr class="my-2">
                <strong>{{ __('messages.user_account_created') }}</strong><br>
                {{ __('messages.temporary_password') }}: <code>{{ session('user_password') }}</code><br>
                <small class="text-muted">{{ __('messages.save_password_message') }}</small>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card table-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.email') }}</th>
                            <th>{{ __('messages.phone') }}</th>
                            <th>{{ __('messages.specialty') }}</th>
<<<<<<< HEAD
                            <th>{{ __('messages.address') }}</th>
=======
>>>>>>> f33f2232176085b0a90bb4c33ebccb726a021b05
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                            <tr>
                                <td>
                                    <i class="fas fa-user-md me-2 text-muted"></i>
                                    <strong>{{ $doctor->name }}</strong>
                                </td>
                                <td>
                                    @if($doctor->email)
                                        <i class="fas fa-envelope me-1 text-muted"></i>{{ $doctor->email }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-phone me-1 text-muted"></i>{{ $doctor->phone }}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $doctor->specialty }}</span>
                                </td>
                                <td>
                                    @if($doctor->address)
                                        <i class="fas fa-map-marker-alt me-1 text-muted"></i>{{ $doctor->address }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-info" title="{{ __('messages.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::check() && (Auth::user()->isAdmin() || (Auth::user()->isDoctor() && Auth::user()->doctor && Auth::user()->doctor->id === $doctor->id)))
                                        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-primary" title="{{ __('messages.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        @if(Auth::check() && Auth::user()->isAdmin())
                                        <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="{{ __('messages.delete') }}" onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fas fa-user-md fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-2">{{ __('messages.no_doctors_found') }}</h5>
                                    @if(Auth::check() && Auth::user()->isAdmin())
                                    <a href="{{ route('admin.doctors.create') }}" class="btn btn-add mt-3">
                                        <i class="fas fa-plus me-2"></i>{{ __('messages.add_doctor') }}
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($doctors->hasPages())
            <div class="card-footer bg-white border-top-0">
                <div class="d-flex justify-content-center">
                    {{ $doctors->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
