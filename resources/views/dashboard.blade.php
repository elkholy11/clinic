@extends('layouts.app')

@section('title', __('messages.dashboard'))

@push('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h4 class="card-title mb-0">
                        {{ __('messages.welcome_to') }} {{ config('app.name', 'Dashboard') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users me-2"></i> {{ __('messages.total_patients') }}</h5>
                    <p class="display-4">{{ $totalPatients }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-user-md me-2"></i> {{ __('messages.total_doctors') }}</h5>
                    <p class="display-4">{{ $totalDoctors }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i> {{ __('messages.total_appointments') }}</h5>
                    <p class="display-4">{{ $totalAppointments }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-file-medical me-2"></i> {{ __('messages.total_reports') }}</h5>
                    <p class="display-4">{{ $totalReports }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card activity-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i> {{ __('messages.recent_appointments') }}</h5>
                    <a href="{{ route('appointments.index') }}" class="btn btn-primary btn-sm">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.patient') }}</th>
                                    <th>{{ __('messages.doctor') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAppointments as $appointment)
                                <tr>
                                    <td><a href="{{ route('patients.show', $appointment->patient) }}" class="text-decoration-none">{{ $appointment->patient->name }}</a></td>
                                    <td><a href="{{ route('doctors.show', $appointment->doctor) }}" class="text-decoration-none">{{ $appointment->doctor->name }}</a></td>
                                    <td>
                                        <span data-bs-toggle="tooltip" title="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d H:i') }}">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-{{ $appointment->status === 'scheduled' ? 'primary' : ($appointment->status === 'completed' ? 'success' : 'danger') }}">{{ __('messages.' . $appointment->status) }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4"><i class="fas fa-calendar-times fa-2x text-muted mb-2"></i><p class="mb-0">{{ __('messages.no_appointments_found') }}</p></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card activity-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-file-medical me-2"></i> {{ __('messages.recent_reports') }}</h5>
                    <a href="{{ route('reports.index') }}" class="btn btn-primary btn-sm">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.patient') }}</th>
                                    <th>{{ __('messages.doctor') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.diagnosis') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReports as $report)
                                <tr>
                                    <td><a href="{{ route('patients.show', $report->patient) }}" class="text-decoration-none">{{ $report->patient->name }}</a></td>
                                    <td><a href="{{ route('doctors.show', $report->doctor) }}" class="text-decoration-none">{{ $report->doctor->name }}</a></td>
                                    <td>
                                        <span data-bs-toggle="tooltip" title="{{ \Carbon\Carbon::parse($report->date)->format('Y-m-d H:i') }}">
                                            {{ \Carbon\Carbon::parse($report->date)->format('Y-m-d') }}
                                        </span>
                                    </td>
                                    <td><span data-bs-toggle="tooltip" title="{{ $report->diagnosis }}">{{ Str::limit($report->diagnosis, 30) }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4"><i class="fas fa-file-medical fa-2x text-muted mb-2"></i><p class="mb-0">{{ __('messages.no_reports_found') }}</p></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush 