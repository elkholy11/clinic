@extends('layouts.app')

@section('title', __('messages.add_medical_file'))

@section('content')
    <h2>{{ __('messages.add_medical_file') }}</h2>

    <form action="{{ route('medical_files.store') }}" method="POST">
        @csrf

        <label>{{ __('messages.patient') }}</label>
        <select name="patient_id" required>
            @foreach ($patients as $patient)
                <option value="{{ $patient->id }}">{{ $patient->name }}</option>
            @endforeach
        </select>

        <label>{{ __('messages.description') }}</label>
        <textarea name="description" required></textarea>

        <button type="submit">{{ __('messages.save') }}</button>
    </form>
@endsection
