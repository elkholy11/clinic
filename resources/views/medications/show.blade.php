@extends('layouts.app')

@section('title', __('messages.view_medication'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(250, 112, 154, 0.15);
    }
    
    .page-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.75rem;
    }
    
    .detail-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .detail-card .card-title {
        color: #fa709a;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .detail-card table {
        margin-bottom: 0;
    }
    
    .detail-card table th {
        width: 40%;
        color: #6c757d;
        font-weight: 500;
    }
    
    .detail-card table td {
        font-weight: 400;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-pills me-2"></i>{{ __('messages.view_medication') }}</h2>
            </div>
            <div>
                @php
                    $user = Auth::user();
                    $role = \App\Helpers\UserHelper::getUserRole($user);
                    $isAdmin = $role['isAdmin'];
                    $isDoctor = $role['isDoctor'];
                    $medicationsRoute = $isAdmin || $isDoctor ? 'admin.medications' : 'user.medications';
                @endphp
                <a href="{{ route($medicationsRoute . '.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>{{ __('messages.medication_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('messages.name') }}:</th>
                            <td>{{ $medication->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.generic_name') }}:</th>
                            <td>{{ $medication->generic_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.dosage') }}:</th>
                            <td>{{ $medication->dosage ? $medication->dosage . ' ' . ($medication->unit ?? '') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.price') }}:</th>
                            <td>{{ number_format($medication->price, 2) }} {{ __('messages.currency') ?? 'EGP' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.stock_quantity') }}:</th>
                            <td>
                                @if($medication->stock_quantity > 0)
                                    <span class="badge bg-success">{{ $medication->stock_quantity }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('messages.out_of_stock') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.expiry_date') }}:</th>
                            <td>
                                @if($medication->expiry_date)
                                    {{ $medication->expiry_date->format('Y-m-d') }}
                                    @if($medication->isExpired())
                                        <span class="badge bg-danger ms-2">{{ __('messages.expired') }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-building me-2"></i>{{ __('messages.additional_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('messages.manufacturer') }}:</th>
                            <td>{{ $medication->manufacturer ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.category') }}:</th>
                            <td>{{ $medication->category ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.requires_prescription') }}:</th>
                            <td>
                                @if($medication->requires_prescription)
                                    <span class="badge bg-warning">{{ __('messages.yes') }}</span>
                                @else
                                    <span class="badge bg-info">{{ __('messages.no') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.status') }}:</th>
                            <td>
                                @if($medication->is_active)
                                    <span class="badge bg-success">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.description') }}:</th>
                            <td>{{ $medication->description ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @can('update', $medication)
            <hr class="my-4">
            <div class="text-end">
                <a href="{{ route('admin.medications.edit', $medication) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.edit') }}
                </a>
                @can('delete', $medication)
                <form action="{{ route('admin.medications.destroy', $medication) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                        <i class="fas fa-trash me-2"></i>{{ __('messages.delete') }}
                    </button>
                </form>
                @endcan
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
