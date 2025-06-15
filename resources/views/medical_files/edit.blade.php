@extends('layouts.app')

@section('title', __('messages.edit_medical_file'))

@section('content')
    <h2>{{ __('messages.edit_medical_file') }}</h2>

    <form action="{{ route('medical_files.update', $medical_file->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>{{ __('messages.patient') }}</label>
        <select name="patient_id" required>
            @foreach ($patients as $patient)
                <option value="{{ $patient->id }}" {{ $medical_file->patient_id == $patient->id ? 'selected' : '' }}>
                    {{ $patient->name }}
                </option>
            @endforeach
        </select>

        <label>{{ __('messages.description') }}</label>
        <textarea name="description" required>{{ $medical_file->description }}</textarea>

        <button type="submit">{{ __('messages.update') }}</button>
    </form>
@endsection
