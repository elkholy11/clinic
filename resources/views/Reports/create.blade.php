@extends('layouts.app')

@section('title', __('messages.create_report'))

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ __('messages.create_report') }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                {{ __('messages.back_to_list') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('reports.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="patient_id" class="form-label">{{ __('messages.patient') }}</label>
                    <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                        <option value="">{{ __('messages.select_patient') }}</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="doctor_id" class="form-label">{{ __('messages.doctor') }}</label>
                    <select class="form-select @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id" required>
                        <option value="">{{ __('messages.select_doctor') }}</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">{{ __('messages.date') }}</label>
                    <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date') }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="diagnosis" class="form-label">{{ __('messages.diagnosis') }}</label>
                    <textarea class="form-control @error('diagnosis') is-invalid @enderror" id="diagnosis" name="diagnosis" rows="3" required>{{ old('diagnosis') }}</textarea>
                    @error('diagnosis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="prescription" class="form-label">{{ __('messages.prescription') }}</label>
                    <textarea class="form-control @error('prescription') is-invalid @enderror" id="prescription" name="prescription" rows="3" required>{{ old('prescription') }}</textarea>
                    @error('prescription')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">{{ __('messages.notes') }}</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">{{ __('messages.create') }}</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
