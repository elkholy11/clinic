@extends('layouts.app')

@section('title', __('messages.invoices'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(245, 87, 108, 0.15);
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
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        background: linear-gradient(90deg, rgba(240, 147, 251, 0.05) 0%, rgba(245, 87, 108, 0.05) 100%);
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
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .action-buttons .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .action-buttons .btn-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
        color: white;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
    }
    
    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(245, 87, 108, 0.4);
        color: white;
    }
    
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(240, 147, 251, 0.02) 0%, rgba(245, 87, 108, 0.02) 100%);
    }
    
    .empty-state i {
        opacity: 0.2;
        margin-bottom: 1.5rem;
        color: #f5576c;
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
    $invoicesRoute = $isAdmin || $isDoctor ? 'admin.invoices' : 'user.invoices';
@endphp
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-file-invoice me-2"></i>{{ __('messages.invoices') }}</h2>
            </div>
            <div>
                @can('create', App\Models\Invoice::class)
                <a href="{{ route('admin.invoices.create') }}" class="btn btn-add">
                    <i class="fas fa-plus me-2"></i>{{ __('messages.add_invoice') }}
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
                            <th>{{ __('messages.invoice_number') }}</th>
                            <th>{{ __('messages.patient') }}</th>
                            <th>{{ __('messages.total_amount') }}</th>
                            <th>{{ __('messages.final_amount') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.issue_date') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                <td>{{ $invoice->patient->name ?? '-' }}</td>
                                <td>{{ number_format($invoice->total_amount, 2) }} {{ __('messages.currency') ?? 'EGP' }}</td>
                                <td><strong>{{ number_format($invoice->final_amount, 2) }} {{ __('messages.currency') ?? 'EGP' }}</strong></td>
                                <td>
                                    @if($invoice->status === 'paid')
                                        <span class="badge bg-success">{{ __('messages.paid') }}</span>
                                    @elseif($invoice->status === 'partial')
                                        <span class="badge bg-warning">{{ __('messages.partial') }}</span>
                                    @elseif($invoice->status === 'cancelled')
                                        <span class="badge bg-danger">{{ __('messages.cancelled') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('messages.pending') }}</span>
                                    @endif
                                </td>
                                <td>{{ $invoice->issue_date ? (\Carbon\Carbon::parse($invoice->issue_date)->format('Y-m-d')) : '-' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        @can('view', $invoice)
                                        <a href="{{ route($invoicesRoute . '.show', $invoice) }}" class="btn btn-info" title="{{ __('messages.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endcan
                                        @can('update', $invoice)
                                        <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-primary" title="{{ __('messages.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete', $invoice)
                                        <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" class="d-inline">
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
                                    <i class="fas fa-file-invoice fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-2">{{ __('messages.no_invoices_found') }}</h5>
                                    @can('create', App\Models\Invoice::class)
                                    <a href="{{ route('admin.invoices.create') }}" class="btn btn-add mt-3">
                                        <i class="fas fa-plus me-2"></i>{{ __('messages.add_invoice') }}
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $invoices->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection


