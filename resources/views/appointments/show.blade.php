@extends('layouts.app')

@section('title', __('messages.appointment_details'))

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ __('messages.appointment_details') }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                {{ __('messages.back_to_list') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">{{ __('messages.patient_information') }}</h5>
                    <table class="table">
                        <tr>
                            <th>{{ __('messages.name') }}</th>
                            <td>{{ $appointment->patient->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.email') }}</th>
                            <td>{{ $appointment->patient->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.phone') }}</th>
                            <td>{{ $appointment->patient->phone }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title">{{ __('messages.doctor_information') }}</h5>
                    <table class="table">
                        <tr>
                            <th>{{ __('messages.name') }}</th>
                            <td>{{ $appointment->doctor->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.specialty') }}</th>
                            <td>{{ $appointment->doctor->specialty }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="card-title">{{ __('messages.appointment_information') }}</h5>
                    <table class="table">
                        <tr>
                            <th>{{ __('messages.date') }}</th>
                            <td>{{ $appointment->appointment_date }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.time') }}</th>
                            <td>{{ $appointment->appointment_time }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.status') }}</th>
                            <td>
                                <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ __('messages.' . $appointment->status) }}
                                </span>
                            </td>
                        </tr>
                        @if($appointment->notes)
                            <tr>
                                <th>{{ __('messages.notes') }}</th>
                                <td>{{ $appointment->notes }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary">
                   <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                </a>
                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="d-inline">
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