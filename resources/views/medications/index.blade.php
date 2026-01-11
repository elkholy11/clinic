@extends('layouts.app')

@section('title', __('messages.medications'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(250, 112, 154, 0.15);
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
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
        background: linear-gradient(90deg, rgba(250, 112, 154, 0.05) 0%, rgba(254, 225, 64, 0.05) 100%);
        transform: translateX(2px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
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
    
    .action-buttons {
        display: flex;
        gap: 0.4rem;
        align-items: center;
        justify-content: flex-start;
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
    
    .action-buttons .btn-info {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }
    
    .action-buttons .btn-info:hover {
        background: linear-gradient(135deg, #f95f8a 0%, #fdd830 100%);
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
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        border: none;
        color: white;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(250, 112, 154, 0.3);
    }
    
    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(250, 112, 154, 0.4);
        color: white;
    }
    
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(250, 112, 154, 0.02) 0%, rgba(254, 225, 64, 0.02) 100%);
    }
    
    .empty-state i {
        opacity: 0.2;
        margin-bottom: 1.5rem;
        color: #fa709a;
    }
    
    .empty-state h5 {
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    
    .card-footer {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 1.25rem;
    }
    
    .badge-status {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
@php
    $user = Auth::user();
    $role = \App\Helpers\UserHelper::getUserRole($user);
    $isAdmin = $role['isAdmin'];
    $isDoctor = $role['isDoctor'];
    $medicationsRoute = $isAdmin || $isDoctor ? 'admin.medications' : 'user.medications';
@endphp
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-pills me-2"></i>{{ __('messages.medications') }}</h2>
            </div>
            <div>
                @can('create', App\Models\Medication::class)
                <a href="{{ route('admin.medications.create') }}" class="btn btn-add">
                    <i class="fas fa-plus me-2"></i>{{ __('messages.add_medication') }}
                </a>
                @endcan
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
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
                            <th>{{ __('messages.generic_name') }}</th>
                            <th>{{ __('messages.dosage') }}</th>
                            <th>{{ __('messages.price') }}</th>
                            <th>{{ __('messages.stock_quantity') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medications as $medication)
                            <tr>
                                <td>{{ $medication->name }}</td>
                                <td>{{ $medication->generic_name ?? '-' }}</td>
                                <td>{{ $medication->dosage ? $medication->dosage . ' ' . ($medication->unit ?? '') : '-' }}</td>
                                <td>{{ number_format($medication->price, 2) }} {{ __('messages.currency') ?? 'EGP' }}</td>
                                <td>
                                    @if($medication->stock_quantity > 0)
                                        <span class="badge bg-success">{{ $medication->stock_quantity }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('messages.out_of_stock') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($medication->is_active)
                                        <span class="badge bg-success">{{ __('messages.active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                    @endif
                                    @if($medication->isExpired())
                                        <span class="badge bg-danger ms-1">{{ __('messages.expired') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @can('view', $medication)
                                        <a href="{{ route($medicationsRoute . '.show', $medication) }}" class="btn btn-info" title="{{ __('messages.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endcan
                                        @can('update', $medication)
                                        <a href="{{ route('admin.medications.edit', $medication) }}" class="btn btn-primary" title="{{ __('messages.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete', $medication)
                                        <form action="{{ route('admin.medications.destroy', $medication) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="{{ __('messages.delete') }}" onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-pills fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-2">{{ __('messages.no_medications_found') }}</h5>
                                    @can('create', App\Models\Medication::class)
                                    <a href="{{ route('admin.medications.create') }}" class="btn btn-add mt-3">
                                        <i class="fas fa-plus me-2"></i>{{ __('messages.add_medication') }}
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($medications->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $medications->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

