@extends('layouts.app')

@section('title', __('messages.patients'))

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ __('messages.patients') }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('patients.create') }}" class="btn btn-primary">
                {{ __('messages.add_patient') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.email') }}</th>
                            <th>{{ __('messages.phone') }}</th>
                            <th>{{ __('messages.date_of_birth') }}</th>
                            <th>{{ __('messages.gender') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->phone }}</td>
                                <td>{{ $patient->date_of_birth }}</td>
                                <td>{{ $patient->gender }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $patient) }}" class="btn btn-info btn-sm" title="{{ __('messages.view') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-primary btn-sm" title="{{ __('messages.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="d-inline">
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
                                <td colspan="6" class="text-center">{{ __('messages.no_patients_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
