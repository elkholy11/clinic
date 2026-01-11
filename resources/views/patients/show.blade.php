@extends('layouts.app')

@section('title', __('messages.patient_details'))

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
                <h2><i class="fas fa-user-injured me-2"></i>{{ __('messages.patient_details') }}</h2>
            </div>
            <div>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-user me-2"></i>{{ __('messages.personal_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('messages.name') }}:</th>
                            <td>{{ $patient->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.email') }}:</th>
                            <td>{{ $patient->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.phone') }}:</th>
                            <td>{{ $patient->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.address') }}:</th>
                            <td>{{ $patient->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.date_of_birth') }}:</th>
                            <td>{{ $patient->birth_date ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.gender') }}:</th>
                            <td>
                                @if($patient->gender)
                                    <span class="badge bg-info">{{ $patient->gender }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-heartbeat me-2"></i>{{ __('messages.medical_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('messages.blood_type') }}:</th>
                            <td>{{ $patient->blood_type ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.allergies') }}:</th>
                            <td>{{ $patient->allergies ?: __('messages.none') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.medical_conditions') }}:</th>
                            <td>{{ $patient->medical_conditions ?: __('messages.none') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @can('update', $patient)
            <hr class="my-4">
            <div class="text-end">
                <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.edit') }}
                </a>
                @can('delete', $patient)
                <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST" class="d-inline">
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
