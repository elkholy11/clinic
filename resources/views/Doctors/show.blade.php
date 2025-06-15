@extends('layouts.app')

@section('title', __('messages.doctor_details'))

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ __('messages.doctor_details') }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('doctors.index') }}" class="btn btn-secondary">
                </i> {{ __('messages.back_to_list') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">{{ __('messages.personal_information') }}</h5>
                    <table class="table">
                        <tr>
                            <th>{{ __('messages.name') }}</th>
                            <td>{{ $doctor->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.email') }}</th>
                            <td>{{ $doctor->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.phone') }}</th>
                            <td>{{ $doctor->phone }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.specialization') }}</th>
                            <td>{{ $doctor->specialty }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.address') }}</th>
                            <td>{{ $doctor->address }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title">{{ __('messages.appointments') }}</h5>
                    @if($doctor->appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.date') }}</th>
                                        <th>{{ __('messages.patient') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($doctor->appointments->take(5) as $appointment)
                                        <tr>
                                            <td>{{ $appointment->appointment_date }}</td>
                                            <td>{{ $appointment->patient->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                    {{ __("messages.{$appointment->status}") }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($doctor->appointments->count() > 5)
                            <div class="text-center mt-2">
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    {{ __('messages.view_all_appointments') }}
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted">{{ __('messages.no_appointments_found') }}</p>
                    @endif
                </div>
            </div>

            <div class=" mt-4">
                <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> {{ __('messages.edit_doctor') }}
                </a>
                <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('messages.delete_confirmation') }}')">
                        <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 