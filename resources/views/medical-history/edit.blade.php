@extends('layouts.app')

@section('title', __('messages.edit_medical_history'))

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
    
    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #43e97b;
        box-shadow: 0 0 0 0.2rem rgba(67, 233, 123, 0.25);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 233, 123, 0.4);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-edit me-2"></i>{{ __('messages.edit_medical_history') }}</h2>
            </div>
            <div>
                <a href="{{ route('admin.medical-history.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card form-card">
        <div class="card-body">
            <form action="{{ route('admin.medical-history.update', $medicalHistory) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">{{ __('messages.patient') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                <option value="">{{ __('messages.select_patient') }}</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $medicalHistory->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="record_type" class="form-label">{{ __('messages.record_type') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('record_type') is-invalid @enderror" id="record_type" name="record_type" required>
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="allergy" {{ old('record_type', $medicalHistory->record_type) == 'allergy' ? 'selected' : '' }}>{{ __('messages.allergy') }}</option>
                                <option value="surgery" {{ old('record_type', $medicalHistory->record_type) == 'surgery' ? 'selected' : '' }}>{{ __('messages.surgery') }}</option>
                                <option value="chronic_disease" {{ old('record_type', $medicalHistory->record_type) == 'chronic_disease' ? 'selected' : '' }}>{{ __('messages.chronic_disease') }}</option>
                                <option value="medication" {{ old('record_type', $medicalHistory->record_type) == 'medication' ? 'selected' : '' }}>{{ __('messages.medication_history') }}</option>
                                <option value="vaccination" {{ old('record_type', $medicalHistory->record_type) == 'vaccination' ? 'selected' : '' }}>{{ __('messages.vaccination') }}</option>
                                <option value="other" {{ old('record_type', $medicalHistory->record_type) == 'other' ? 'selected' : '' }}>{{ __('messages.other') }}</option>
                            </select>
                            @error('record_type')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('messages.title') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $medicalHistory->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="form-label">{{ __('messages.date') }}</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $medicalHistory->date?->format('Y-m-d')) }}">
                            @error('date')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">{{ __('messages.doctor') }}</label>
                            <select class="form-select @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id">
                                <option value="">{{ __('messages.select_doctor') }}</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id', $medicalHistory->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="attachments" class="form-label">{{ __('messages.attachments') }}</label>
                            <input type="text" class="form-control @error('attachments') is-invalid @enderror" id="attachments" name="attachments" value="{{ old('attachments', $medicalHistory->attachments) }}" placeholder="رابط أو مسار الملف">
                            @error('attachments')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">{{ __('messages.description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $medicalHistory->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-2"></i>{{ __('messages.update') }}
                    </button>
                    <a href="{{ route('admin.medical-history.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>{{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


