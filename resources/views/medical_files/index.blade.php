@extends('layouts.app')

@section('title', __('messages.medical_files'))

@section('content')
    <h2>{{ __('messages.medical_files_list') }}</h2>

    <a href="{{ route('medical_files.create') }}" class="btn btn-primary">{{ __('messages.add_medical_file') }}</a>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.patient') }}</th>
                <th>{{ __('messages.description') }}</th>
                <th>{{ __('messages.created_at') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($medical_files as $file)
                <tr>
                    <td>{{ $file->patient->name }}</td>
                    <td>{{ $file->description }}</td>
                    <td>{{ $file->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('medical_files.edit', $file->id) }}">{{ __('messages.edit') }}</a> |
                        <form action="{{ route('medical_files.destroy', $file->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">{{ __('messages.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
