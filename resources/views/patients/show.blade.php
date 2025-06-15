@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ __('messages.patient_details') }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                {{ __('messages.back_to_list') }}
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
                            <td>{{ $patient->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.email') }}</th>
                            <td>{{ $patient->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.phone') }}</th>
                            <td>{{ $patient->phone }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.address') }}</th>
                            <td>{{ $patient->address }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.date_of_birth') }}</th>
                            <td>{{ $patient->date_of_birth }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.gender') }}</th>
                            <td>{{ $patient->gender }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title">{{ __('messages.medical_information') }}</h5>
                    <table class="table">
                        <tr>
                            <th>{{ __('messages.blood_type') }}</th>
                            <td>{{ $patient->blood_type }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.allergies') }}</th>
                            <td>{{ $patient->allergies ?: __('messages.none') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.medical_conditions') }}</th>
                            <td>{{ $patient->medical_conditions ?: __('messages.none') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                </a>
                <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="d-inline">
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