@extends('layouts.app')

@section('title', __('messages.view_prescription'))

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
    }
    
    .detail-card .card-title {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .detail-card table {
        margin-bottom: 0;
    }
    
    .detail-card table th {
        width: 40%;
        color: #6c757d;
        font-weight: 500;
    }
    
    .detail-card table td {
        font-weight: 400;
    }
    
    .items-table {
        margin-top: 1rem;
    }
    
    .items-table th {
        background: #f8f9fa;
    }
</style>
@endpush

@section('content')
@php
    $user = Auth::user();
    $role = \App\Helpers\UserHelper::getUserRole($user);
    $isAdmin = $role['isAdmin'];
    $isDoctor = $role['isDoctor'];
    $prescriptionsRoute = $isAdmin || $isDoctor ? 'admin.prescriptions' : 'user.prescriptions';
@endphp
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-prescription me-2"></i>{{ __('messages.view_prescription') }}</h2>
            </div>
            <div>
                <a href="{{ route($prescriptionsRoute . '.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>{{ __('messages.prescription_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('messages.patient') }}:</th>
                            <td>{{ $prescription->patient->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.doctor') }}:</th>
                            <td>{{ $prescription->doctor->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.prescription_date') }}:</th>
                            <td>{{ $prescription->prescription_date->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.status') }}:</th>
                            <td>
                                @if($prescription->status == 'active')
                                    <span class="badge bg-success">{{ __('messages.active') }}</span>
                                @elseif($prescription->status == 'completed')
                                    <span class="badge bg-info">{{ __('messages.completed') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('messages.cancelled') }}</span>
                                @endif
                            </td>
                        </tr>
                        @if($prescription->report)
                        <tr>
                            <th>{{ __('messages.report') }}:</th>
                            <td><a href="{{ route('admin.reports.show', $prescription->report) }}">{{ __('messages.view_report') }}</a></td>
                        </tr>
                        @endif
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-sticky-note me-2"></i>{{ __('messages.notes') }}</h5>
                    <p>{{ $prescription->notes ?? '-' }}</p>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="card-title"><i class="fas fa-list me-2"></i>{{ __('messages.items') }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered items-table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.medication') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.dosage') }}</th>
                            <th>{{ __('messages.frequency') }}</th>
                            <th>{{ __('messages.duration') }}</th>
                            <th>{{ __('messages.instructions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescription->items as $item)
                            <tr>
                                <td>{{ $item->medication->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->dosage ?? '-' }}</td>
                                <td>{{ $item->frequency ?? '-' }}</td>
                                <td>{{ $item->duration ? $item->duration . ' ' . __('messages.days') : '-' }}</td>
                                <td>{{ $item->instructions ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">{{ __('messages.no_items') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @can('update', $prescription)
            <hr class="my-4">
            <div class="text-end">
                <a href="{{ route('admin.prescriptions.edit', $prescription) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.edit') }}
                </a>
                @can('delete', $prescription)
                <form action="{{ route('admin.prescriptions.destroy', $prescription) }}" method="POST" class="d-inline">
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


