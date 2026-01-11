@extends('layouts.app')

@section('title', __('messages.doctor_details'))

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
    
    .detail-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .detail-card .card-title {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e9ecef;
        font-size: 1.1rem;
    }
    
    .detail-card .card-title i {
        margin-right: 0.5rem;
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
        background-color: rgba(102, 126, 234, 0.03);
    }
    
    .detail-card table th {
        width: 40%;
        color: #6c757d;
        font-weight: 500;
        padding: 1rem 1rem 1rem 0;
        vertical-align: middle;
    }
    
    .detail-card table td {
        font-weight: 400;
        padding: 1rem 0;
        color: #212529;
        vertical-align: middle;
    }
    
    .appointments-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        min-height: 300px;
    }
    
    .appointments-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .appointments-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .appointments-table thead th {
        border: none;
        padding: 0.875rem 1rem;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .appointments-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .appointments-table tbody tr:last-child {
        border-bottom: none;
    }
    
    .appointments-table tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        transform: translateX(2px);
    }
    
    .appointments-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #495057;
    }
    
    .empty-appointments {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    
    .empty-appointments i {
        font-size: 3rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }
    
    .badge {
        padding: 0.5rem 0.875rem;
        font-weight: 500;
        border-radius: 6px;
    }
    
    .btn-view-all {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    
    .btn-view-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    @media (max-width: 768px) {
        .page-header {
            padding: 1.25rem 1.5rem;
        }
        
        .page-header h2 {
            font-size: 1.5rem;
        }
        
        .detail-card table th,
        .detail-card table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.9rem;
        }
        
        .appointments-section {
            margin-top: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-user-md me-2"></i>{{ __('messages.doctor_details') }}</h2>
            </div>
            <div>
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">
                        <i class="fas fa-user me-2"></i>{{ __('messages.personal_information') }}
                    </h5>
                    <table class="table table-borderless">
                        <tr>
                            <th><i class="fas fa-user me-2 text-muted"></i>{{ __('messages.name') }}:</th>
                            <td><strong>{{ $doctor->name }}</strong></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope me-2 text-muted"></i>{{ __('messages.email') }}:</th>
                            <td>{{ $doctor->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone me-2 text-muted"></i>{{ __('messages.phone') }}:</th>
                            <td>{{ $doctor->phone }}</td>
                        </tr>
                        <tr>
<<<<<<< HEAD
                            <th><i class="fas fa-stethoscope me-2 text-muted"></i>{{ __('messages.specialty') }}:</th>
                            <td>
                                <span class="badge bg-info">{{ $doctor->specialty }}</span>
                            </td>
=======
                            <th>{{ __('messages.specialty') }}</th>
                            <td>{{ $doctor->specialty }}</td>
>>>>>>> f33f2232176085b0a90bb4c33ebccb726a021b05
                        </tr>
                        <tr>
                            <th><i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ __('messages.address') }}:</th>
                            <td>{{ $doctor->address ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-check me-2"></i>{{ __('messages.appointments') }}
                    </h5>
                    <div class="appointments-section">
                        @if($doctor->appointments && $doctor->appointments->count() > 0)
                            <div class="appointments-table">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.date') }}</th>
                                            <th>{{ __('messages.patient') }}</th>
                                            <th>{{ __('messages.status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($doctor->appointments->take(5) as $appointment)
                                            <tr>
                                                <td>
                                                    <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                                    {{ $appointment->appointment_date }}
                                                </td>
                                                <td>
                                                    <i class="fas fa-user-injured me-1 text-muted"></i>
                                                    {{ $appointment->patient->name }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                        {{ __("messages.{$appointment->status}") }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($doctor->appointments->count() > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.appointments.index', ['doctor_id' => $doctor->id]) }}" class="btn btn-view-all">
                                        <i class="fas fa-eye me-2"></i>{{ __('messages.view_all_appointments') }}
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="empty-appointments">
                                <i class="fas fa-calendar-times"></i>
                                <p class="mb-0 mt-2">{{ __('messages.no_appointments_found') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if(Auth::check() && (Auth::user()->isAdmin() || (Auth::user()->isDoctor() && Auth::user()->doctor && Auth::user()->doctor->id === $doctor->id)))
            <hr class="my-4">
            <div class="text-end">
                <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.edit') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
<<<<<<< HEAD
@endsection
=======
@endsection 
>>>>>>> f33f2232176085b0a90bb4c33ebccb726a021b05
