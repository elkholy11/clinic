@extends('layouts.app')

@section('title', 'User Dashboard')

@push('styles')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(79, 172, 254, 0.15);
    }
    
    .stats-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        animation: fadeInUp 0.6s ease;
        animation-fill-mode: both;
    }
    
    .stats-card:nth-child(1) { animation-delay: 0.1s; }
    .stats-card:nth-child(2) { animation-delay: 0.2s; }
    .stats-card:nth-child(3) { animation-delay: 0.3s; }
    .stats-card:nth-child(4) { animation-delay: 0.4s; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .stats-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }
    
    .stats-card .card-body {
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .stats-card .card-body::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stats-card:hover .card-body::before {
        opacity: 1;
    }
    
    .stats-card .card-title {
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 0.75rem;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stats-card .display-4 {
        font-size: 3rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .activity-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        animation: fadeIn 0.8s ease;
        animation-fill-mode: both;
    }
    
    .activity-card:nth-child(1) { animation-delay: 0.5s; }
    .activity-card:nth-child(2) { animation-delay: 0.6s; }
    .activity-card:nth-child(3) { animation-delay: 0.7s; }
    .activity-card:nth-child(4) { animation-delay: 0.8s; }
    .activity-card:nth-child(5) { animation-delay: 0.9s; }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .activity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(79, 172, 254, 0.15);
    }
    
    .activity-card .card-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }
    
    .activity-card .card-header .btn {
        border-radius: 20px;
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
    }
    
    .activity-card .table {
        margin-bottom: 0;
    }
    
    .activity-card .table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .activity-card .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2">
                <i class="fas fa-user me-2"></i>{{ __('messages.dashboard') }}
            </h2>
                <p class="mb-0 opacity-75">{{ __('messages.welcome_to') }} {{ Auth::user()->name }}'s Dashboard</p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark px-3 py-2">
                    <i class="fas fa-user me-2"></i>{{ __('messages.user') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Main -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i>{{ __('messages.total_appointments') }}</h5>
                    <h1 class="display-4">{{ $totalAppointments }}</h1>
                    <a href="{{ route('user.appointments.index') }}" class="text-white text-decoration-none small">
                        <i class="fas fa-arrow-right me-1"></i>{{ __('messages.view_all') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-file-medical me-2"></i>{{ __('messages.total_reports') }}</h5>
                    <h1 class="display-4">{{ $totalReports }}</h1>
                    <a href="{{ route('user.reports.index') }}" class="text-white text-decoration-none small">
                        <i class="fas fa-arrow-right me-1"></i>{{ __('messages.view_all') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-prescription me-2"></i>{{ __('messages.prescriptions') }}</h5>
                    <h1 class="display-4">{{ $totalPrescriptions }}</h1>
                    <a href="{{ route('user.prescriptions.index') }}" class="text-white text-decoration-none small">
                        <i class="fas fa-arrow-right me-1"></i>{{ __('messages.view_all') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); color: white;">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-history me-2"></i>{{ __('messages.medical_history') }}</h5>
                    <h1 class="display-4">{{ $totalMedicalHistory }}</h1>
                    <a href="{{ route('user.medical-history.index') }}" class="text-white text-decoration-none small">
                        <i class="fas fa-arrow-right me-1"></i>{{ __('messages.view_all') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Financial -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-file-invoice me-2"></i>{{ __('messages.invoices') }}</h5>
                    <h1 class="display-4">{{ $totalInvoices }}</h1>
                    <small class="d-block mt-2">
                        <span class="badge bg-success">{{ $paidInvoices }} {{ __('messages.paid') }}</span>
                        <span class="badge bg-warning">{{ $pendingInvoices }} {{ __('messages.pending') }}</span>
                    </small>
                    <a href="{{ route('user.invoices.index') }}" class="text-dark text-decoration-none small d-block mt-2">
                        <i class="fas fa-arrow-right me-1"></i>{{ __('messages.view_all') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-money-bill-wave me-2"></i>{{ __('messages.payments') }}</h5>
                    <h1 class="display-4">{{ $totalPayments }}</h1>
                    <a href="{{ route('user.payments.index') }}" class="text-dark text-decoration-none small">
                        <i class="fas fa-arrow-right me-1"></i>{{ __('messages.view_all') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-chart-line me-2"></i>{{ __('messages.total_revenue') }}</h5>
                    <h1 class="display-4" style="font-size: 2rem;">{{ number_format($totalRevenue, 2) }}</h1>
                    <small class="d-block mt-2 text-muted">{{ __('messages.currency') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stats-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-file-invoice-dollar me-2"></i>{{ __('messages.total_invoices_amount') }}</h5>
                    <h1 class="display-4" style="font-size: 2rem;">{{ number_format($totalInvoiceAmount, 2) }}</h1>
                    <small class="d-block mt-2 text-muted">{{ __('messages.currency') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4">
        <!-- Recent Appointments -->
        <div class="col-md-6 col-lg-4">
            <div class="card activity-card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>{{ __('messages.recent_appointments') }}</h5>
                    <a href="{{ route('user.appointments.index') }}" class="btn btn-light btn-sm">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.doctor') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAppointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->doctor->name ?? '-' }}</td>
                                        <td>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ __('messages.' . $appointment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">{{ __('messages.no_appointments_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="col-md-6 col-lg-4">
            <div class="card activity-card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-file-medical me-2"></i>{{ __('messages.recent_reports') }}</h5>
                    <a href="{{ route('user.reports.index') }}" class="btn btn-light btn-sm">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.doctor') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.diagnosis') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReports as $report)
                                    <tr>
                                        <td>{{ $report->doctor->name ?? '-' }}</td>
                                        <td>{{ $report->date ?? '-' }}</td>
                                        <td>{{ Str::limit($report->diagnosis ?? '-', 25) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">{{ __('messages.no_reports_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Prescriptions -->
        <div class="col-md-6 col-lg-4">
            <div class="card activity-card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-prescription me-2"></i>{{ __('messages.recent_prescriptions') ?? 'Recent Prescriptions' }}</h5>
                    <a href="{{ route('user.prescriptions.index') }}" class="btn btn-light btn-sm">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.doctor') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPrescriptions as $prescription)
                                    <tr>
                                        <td>{{ $prescription->doctor->name ?? '-' }}</td>
                                        <td>{{ $prescription->prescription_date ? \Carbon\Carbon::parse($prescription->prescription_date)->format('Y-m-d') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $prescription->status === 'completed' ? 'success' : ($prescription->status === 'cancelled' ? 'danger' : 'info') }}">
                                                {{ __('messages.' . $prescription->status) ?? $prescription->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">{{ __('messages.no_prescriptions_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="col-md-6 col-lg-4">
            <div class="card activity-card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-file-invoice me-2"></i>{{ __('messages.recent_invoices') }}</h5>
                    <a href="{{ route('user.invoices.index') }}" class="btn btn-light btn-sm">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.invoice_number') }}</th>
                                    <th>{{ __('messages.amount') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number ?? '-' }}</td>
                                        <td>{{ number_format($invoice->final_amount ?? 0, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'cancelled' ? 'danger' : ($invoice->status === 'partial' ? 'info' : 'warning')) }}">
                                                {{ __('messages.' . $invoice->status) ?? $invoice->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">{{ __('messages.no_invoices_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="col-md-6 col-lg-4">
            <div class="card activity-card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-money-bill-wave me-2"></i>{{ __('messages.recent_payments') }}</h5>
                    <a href="{{ route('user.payments.index') }}" class="btn btn-light btn-sm">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.amount') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.method') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                    <tr>
                                        <td>{{ number_format($payment->amount ?? 0, 2) }}</td>
                                        <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ __('messages.' . $payment->payment_method) ?? $payment->payment_method }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">{{ __('messages.no_payments_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card activity-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>{{ __('messages.quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('user.appointments.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                            <i class="fas fa-plus me-2"></i>{{ __('messages.add_appointment') }}
                        </a>
                        <a href="{{ route('user.appointments.index') }}" class="btn btn-outline-primary" style="border: 2px solid #4facfe; color: #4facfe; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                            <i class="fas fa-calendar me-2"></i>{{ __('messages.view_all_appointments') }}
                        </a>
                        <a href="{{ route('user.reports.index') }}" class="btn btn-outline-info" style="border: 2px solid #17a2b8; color: #17a2b8; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                            <i class="fas fa-file-medical me-2"></i>{{ __('messages.view_reports') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

