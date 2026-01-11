@extends('layouts.app')

@section('title', __('messages.report_details'))

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
    
    .detail-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .detail-card .card-title {
        color: #fa709a;
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
        background-color: rgba(250, 112, 154, 0.03);
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
    
    .detail-card table th.align-top,
    .detail-card table td.align-top {
        vertical-align: top;
        padding-top: 1.25rem;
    }
    
    .info-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .info-section:last-child {
        margin-bottom: 0;
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
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-file-medical me-2"></i>{{ __('messages.report_details') }}</h2>
            </div>
            <div>
                @if(Auth::user()->isAdmin() || Auth::user()->isDoctor())
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                    </a>
                @else
                    <a href="{{ route('user.reports.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body">
            <!-- Patient Information -->
            <div class="info-section">
                <h5 class="card-title">
                    <i class="fas fa-user-injured me-2"></i>{{ __('messages.patient_information') }}
                </h5>
                <div class="row">
                    <div class="col-md-4">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th><i class="fas fa-user me-2 text-muted"></i>{{ __('messages.name') }}:</th>
                                <td><strong>{{ $report->patient->name ?? 'N/A' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th><i class="fas fa-envelope me-2 text-muted"></i>{{ __('messages.email') }}:</th>
                                <td>{{ $report->patient->email ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th><i class="fas fa-phone me-2 text-muted"></i>{{ __('messages.phone') }}:</th>
                                <td>{{ $report->patient->phone ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Doctor Information -->
            <div class="info-section">
                <h5 class="card-title">
                    <i class="fas fa-user-md me-2"></i>{{ __('messages.doctor_information') }}
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th><i class="fas fa-user me-2 text-muted"></i>{{ __('messages.name') }}:</th>
                                <td><strong>{{ $report->doctor->name ?? 'N/A' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th><i class="fas fa-stethoscope me-2 text-muted"></i>{{ __('messages.specialty') }}:</th>
                                <td><span class="badge bg-info">{{ $report->doctor->specialty ?? 'N/A' }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Report Information -->
            <div class="info-section">
                <h5 class="card-title">
                    <i class="fas fa-file-alt me-2"></i>{{ __('messages.report_information') }}
                </h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th><i class="fas fa-calendar-alt me-2 text-muted"></i>{{ __('messages.date') }}:</th>
                                <td><strong>{{ $report->date ?? 'N/A' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12 mb-3">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th class="align-top"><i class="fas fa-stethoscope me-2 text-muted"></i>{{ __('messages.diagnosis') }}:</th>
                                <td class="align-top">{{ $report->diagnosis ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12 mb-3">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th class="align-top"><i class="fas fa-pills me-2 text-muted"></i>{{ __('messages.prescription') }}:</th>
                                <td class="align-top">{{ $report->prescription ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    @if($report->notes)
                    <div class="col-md-12">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th class="align-top"><i class="fas fa-sticky-note me-2 text-muted"></i>{{ __('messages.notes') }}:</th>
                                <td class="align-top">{{ $report->notes }}</td>
                            </tr>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
