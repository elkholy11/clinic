@extends('layouts.app')

@section('title', __('messages.reports'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(240, 147, 251, 0.15);
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
    
    .table tbody td:first-child {
        font-weight: 600;
        color: #212529;
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
        box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3);
    }
    
    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(240, 147, 251, 0.4);
        color: white;
    }
    
    .btn-export {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        border: none;
        color: white;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(67, 233, 123, 0.3);
    }
    
    .btn-export:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(67, 233, 123, 0.4);
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
        color: #f093fb;
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
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-file-medical me-2"></i>{{ __('messages.reports_list') }}</h2>
            </div>
            <div class="d-flex gap-2">
                @if(Auth::user()->isAdmin())
                    <form method="GET" action="{{ route('admin.reports.index') }}" class="d-inline">
                        @foreach(request()->except('export') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <button type="submit" name="export" value="csv" class="btn btn-export">
                            <i class="fas fa-file-excel me-2"></i>{{ __('messages.export') }}
                        </button>
                    </form>
                    <a href="{{ route('admin.reports.create') }}" class="btn btn-add">
                        <i class="fas fa-plus me-2"></i>{{ __('messages.add_report') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card table-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.patient') }}</th>
                            <th>{{ __('messages.doctor') }}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.diagnosis') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>{{ $report->patient->name }}</td>
                                <td>{{ $report->doctor->name }}</td>
                                <td>{{ $report->date }}</td>
                                <td>{{ Str::limit($report->diagnosis, 50) }}</td>
                                <td>
                                    <div class="action-buttons">
                                        @if(Auth::user()->isAdmin())
                                            <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-info" title="{{ __('messages.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('update', $report)
                                            <a href="{{ route('admin.reports.edit', $report) }}" class="btn btn-primary" title="{{ __('messages.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('delete', $report)
                                            <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="{{ __('messages.delete') }}" onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        @elseif(Auth::user()->isDoctor())
                                            <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-info" title="{{ __('messages.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('user.reports.show', $report) }}" class="btn btn-info" title="{{ __('messages.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-2">{{ __('messages.no_records_found') }}</h5>
                                    @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.reports.create') }}" class="btn btn-add mt-3">
                                        <i class="fas fa-plus me-2"></i>{{ __('messages.add_report') }}
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $reports->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
