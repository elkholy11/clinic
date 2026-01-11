@extends('layouts.app')

@section('title', __('messages.view_medical_history'))

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
@php
    $user = Auth::user();
    $role = \App\Helpers\UserHelper::getUserRole($user);
    $isAdmin = $role['isAdmin'];
    $isDoctor = $role['isDoctor'];
    $medicalHistoryRoute = $isAdmin || $isDoctor ? 'admin.medical-history' : 'user.medical-history';
@endphp
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-history me-2"></i>{{ __('messages.view_medical_history') }}</h2>
            </div>
            <div>
                <a href="{{ route($medicalHistoryRoute . '.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>{{ __('messages.record_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('messages.patient') }}:</th>
                            <td>{{ $medicalHistory->patient->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.record_type') }}:</th>
                            <td>
                                @php
                                    $types = [
                                        'allergy' => __('messages.allergy'),
                                        'surgery' => __('messages.surgery'),
                                        'chronic_disease' => __('messages.chronic_disease'),
                                        'medication' => __('messages.medication_history'),
                                        'vaccination' => __('messages.vaccination'),
                                        'other' => __('messages.other'),
                                    ];
                                @endphp
                                <span class="badge bg-info">{{ $types[$medicalHistory->record_type] ?? $medicalHistory->record_type }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.title') }}:</th>
                            <td>{{ $medicalHistory->title }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.date') }}:</th>
                            <td>{{ $medicalHistory->date ? $medicalHistory->date->format('Y-m-d') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.doctor') }}:</th>
                            <td>{{ $medicalHistory->doctor ? $medicalHistory->doctor->name : '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-file-alt me-2"></i>{{ __('messages.details') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('messages.description') }}:</th>
                            <td>{{ $medicalHistory->description ?? '-' }}</td>
                        </tr>
                        @if($medicalHistory->attachments)
                        <tr>
                            <th>{{ __('messages.attachments') }}:</th>
                            <td>
                                <a href="{{ $medicalHistory->attachments }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>{{ __('messages.view_attachment') }}
                                </a>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>{{ __('messages.created_at') }}:</th>
                            <td>{{ $medicalHistory->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.last_updated') }}:</th>
                            <td>{{ $medicalHistory->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @can('update', $medicalHistory)
            <hr class="my-4">
            <div class="text-end">
                <a href="{{ route('admin.medical-history.edit', $medicalHistory) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.edit') }}
                </a>
                @can('delete', $medicalHistory)
                <form action="{{ route('admin.medical-history.destroy', $medicalHistory) }}" method="POST" class="d-inline">
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


