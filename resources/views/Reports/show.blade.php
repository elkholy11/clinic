@extends('layouts.app')

@section('title', __('messages.report_details'))

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ __('messages.report_details') }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                {{ __('messages.back_to_list') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>{{ __('messages.patient_information') }}</h4>
                    <p><strong>{{ __('messages.name') }}:</strong> {{ $report->patient->name }}</p>
                    <p><strong>{{ __('messages.email') }}:</strong> {{ $report->patient->email }}</p>
                    <p><strong>{{ __('messages.phone') }}:</strong> {{ $report->patient->phone }}</p>
                </div>
                <div class="col-md-6">
                    <h4>{{ __('messages.doctor_information') }}</h4>
                    <p><strong>{{ __('messages.name') }}:</strong> {{ $report->doctor->name }}</p>
                    <p><strong>{{ __('messages.specialty') }}:</strong> {{ $report->doctor->specialty }}</p>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-12">
                    <h4>{{ __('messages.report_information') }}</h4>
                    <p><strong>{{ __('messages.date') }}:</strong> {{ $report->date }}</p>
                    <p><strong>{{ __('messages.diagnosis') }}:</strong> {{ $report->diagnosis }}</p>
                    <p><strong>{{ __('messages.prescription') }}:</strong> {{ $report->prescription }}</p>
                    @if($report->notes)
                        <p><strong>{{ __('messages.notes') }}:</strong> {{ $report->notes }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('reports.edit', $report) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> {{ __('messages.edit') }}</a>
                <form action="{{ route('reports.destroy', $report) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                        {{ __('messages.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 