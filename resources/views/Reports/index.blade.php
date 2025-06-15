@extends('layouts.app')

@section('title', __('messages.reports'))

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ __('messages.reports_list') }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('reports.create') }}" class="btn btn-primary">
                {{ __('messages.add_report') }}
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
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.patient') }}</th>
                            <th>{{ __('messages.doctor') }}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.diagnosis') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>{{ $report->patient->name }}</td>
                                <td>{{ $report->doctor->name }}</td>
                                <td>{{ $report->date }}</td>
                                <td>{{ Str::limit($report->diagnosis, 50) }}</td>
                                <td>
                                    <a href="{{ route('reports.show', $report) }}" class="btn btn-info btn-sm" title="{{ __('messages.view') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('reports.edit', $report) }}" class="btn btn-primary btn-sm" title="{{ __('messages.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('reports.destroy', $report) }}" method="POST" class="d-inline">
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
                                <td colspan="5" class="text-center">{{ __('messages.no_records_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
