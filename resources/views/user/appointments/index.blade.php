@extends('layouts.app')

@section('title', __('messages.appointments'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(79, 172, 254, 0.15);
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
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
        background: linear-gradient(90deg, rgba(79, 172, 254, 0.05) 0%, rgba(0, 242, 254, 0.05) 100%);
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
    }
    
    .btn-view {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }
    
    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(23, 162, 184, 0.4);
        color: white;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        color: white;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
    
    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        color: white;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        color: white;
    }
    
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }
    
    .empty-state p {
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 2rem;
    }
    
    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-calendar-check me-2"></i>{{ __('messages.appointments') }}</h2>
            </div>
            <div>
                <a href="{{ route('user.appointments.create') }}" class="btn btn-add">
                    <i class="fas fa-plus me-2"></i>{{ __('messages.add_appointment') }}
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card table-card">
        <div class="card-body">
            @if($appointments->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>{{ __('messages.no_appointments_found') }}</p>
                    <a href="{{ route('user.appointments.create') }}" class="btn btn-add">
                        <i class="fas fa-plus me-2"></i>{{ __('messages.add_appointment') }}
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.doctor') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.time') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ __('messages.' . $appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('user.appointments.show', $appointment) }}" class="btn btn-view" title="{{ __('messages.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('user.appointments.edit', $appointment) }}" class="btn btn-edit" title="{{ __('messages.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('user.appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete" title="{{ __('messages.delete') }}" onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4 pb-3">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
