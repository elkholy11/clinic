@extends('layouts.app')

@section('title', __('messages.appointment_details'))

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
    
    .detail-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .detail-card .card-title {
        color: #4facfe;
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
        background-color: rgba(79, 172, 254, 0.03);
    }
    
    .detail-card table th {
        width: 40%;
        color: #6c757d;
        font-weight: 500;
    }
    
    .detail-card table td {
        font-weight: 400;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        color: white;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        color: white;
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
                <a href="{{ route('user.appointments.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-user-md me-2"></i>{{ __('messages.doctor_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th><i class="fas fa-user me-2 text-muted"></i>{{ __('messages.name') }}:</th>
                            <td><strong>{{ $appointment->doctor->name ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-stethoscope me-2 text-muted"></i>{{ __('messages.specialty') }}:</th>
                            <td>{{ $appointment->doctor->specialty ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone me-2 text-muted"></i>{{ __('messages.phone') }}:</th>
                            <td>{{ $appointment->doctor->phone ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-calendar-alt me-2"></i>{{ __('messages.appointment_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th><i class="fas fa-calendar me-2 text-muted"></i>{{ __('messages.date') }}:</th>
                            <td><strong>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-clock me-2 text-muted"></i>{{ __('messages.time') }}:</th>
                            <td>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') : 'N/A' }}</td>
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

            <div class="mt-4 pt-3 border-top">
                <a href="{{ route('user.appointments.edit', $appointment) }}" class="btn btn-edit">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.edit') }}
                </a>
                <form action="{{ route('user.appointments.destroy', $appointment) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete" onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                        <i class="fas fa-trash me-2"></i>{{ __('messages.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
