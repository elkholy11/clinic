@extends('layouts.app')

@section('title', __('messages.appointment_details'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(67, 233, 123, 0.15);
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
        color: #43e97b;
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
        background-color: rgba(67, 233, 123, 0.03);
    }
    
    .detail-card table th {
        width: 40%;
        color: #6c757d;
        font-weight: 500;
    }
    
    .detail-card table td {
        font-weight: 400;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-calendar-check me-2"></i>{{ __('messages.appointment_details') }}</h2>
            </div>
            <div>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-user-injured me-2"></i>{{ __('messages.patient_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th><i class="fas fa-user me-2 text-muted"></i>{{ __('messages.name') }}:</th>
                            <td><strong>{{ $appointment->patient->name }}</strong></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope me-2 text-muted"></i>{{ __('messages.email') }}:</th>
                            <td>{{ $appointment->patient->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone me-2 text-muted"></i>{{ __('messages.phone') }}:</th>
                            <td>{{ $appointment->patient->phone ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-user-md me-2"></i>{{ __('messages.doctor_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th><i class="fas fa-user me-2 text-muted"></i>{{ __('messages.name') }}:</th>
                            <td><strong>{{ $appointment->doctor->name }}</strong></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-stethoscope me-2 text-muted"></i>{{ __('messages.specialty') }}:</th>
                            <td><span class="badge bg-info">{{ $appointment->doctor->specialty }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr class="my-4">

            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>{{ __('messages.appointment_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th><i class="fas fa-calendar-alt me-2 text-muted"></i>{{ __('messages.date') }}:</th>
                            <td>{{ $appointment->appointment_date }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-clock me-2 text-muted"></i>{{ __('messages.time') }}:</th>
                            <td>{{ $appointment->appointment_time }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-info-circle me-2 text-muted"></i>{{ __('messages.status') }}:</th>
                            <td>
                                <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ __('messages.' . $appointment->status) }}
                                </span>
                            </td>
                        </tr>
                        @if($appointment->notes)
                            <tr>
                                <th><i class="fas fa-sticky-note me-2 text-muted"></i>{{ __('messages.notes') }}:</th>
                                <td>{{ $appointment->notes }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            @can('update', $appointment)
            <hr class="my-4">
            <div class="text-end">
                <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.edit') }}
                </a>
                @can('delete', $appointment)
                <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                        <i class="fas fa-trash me-2"></i>{{ __('messages.delete') }}
                    </button>
                </form>
                @endcan
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
