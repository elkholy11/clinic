@extends('layouts.app')

@section('title', __('messages.appointments'))

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ __('messages.appointments') }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                {{ __('messages.add_appointment') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('messages.patient') }}</th>
                            <th>{{ __('messages.doctor') }}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.time') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->patient->name }}</td>
                                <td>{{ $appointment->doctor->name }}</td>
                                <td>{{ $appointment->appointment_date }}</td>
                                <td>{{ $appointment->appointment_time }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ __('messages.' . $appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                     <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-info btn-sm" title="{{ __('messages.view') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary btn-sm" title="{{ __('messages.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="{{ __('messages.delete') }}" onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ __('messages.no_appointments_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
