@extends('layouts.app')

@section('title', __('messages.view_payment'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(79, 172, 254, 0.15);
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
        color: #4facfe;
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
@php
    $user = Auth::user();
    $role = \App\Helpers\UserHelper::getUserRole($user);
    $isAdmin = $role['isAdmin'];
    $isDoctor = $role['isDoctor'];
    $paymentsRoute = $isAdmin || $isDoctor ? 'admin.payments' : 'user.payments';
@endphp
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-money-bill-wave me-2"></i>{{ __('messages.view_payment') }}</h2>
            </div>
            <div>
                <a href="{{ route($paymentsRoute . '.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>{{ __('messages.payment_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('messages.invoice') }}:</th>
                            <td>
                                <a href="{{ route($isAdmin || $isDoctor ? 'admin.invoices.show' : 'user.invoices.show', $payment->invoice) }}">
                                    <strong>{{ $payment->invoice->invoice_number }}</strong>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.patient') }}:</th>
                            <td>{{ $payment->patient->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.amount') }}:</th>
                            <td><strong>{{ number_format($payment->amount, 2) }} {{ __('messages.currency') ?? 'EGP' }}</strong></td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.payment_method') }}:</th>
                            <td>
                                <span class="badge bg-info">{{ __('messages.' . $payment->payment_method) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.payment_date') }}:</th>
                            <td>{{ $payment->payment_date ? (\Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d')) : '-' }}</td>
                        </tr>
                        @if($payment->transaction_id)
                        <tr>
                            <th>{{ __('messages.transaction_id') }}:</th>
                            <td>{{ $payment->transaction_id }}</td>
                        </tr>
                        @endif
                        @if($payment->receipt_number)
                        <tr>
                            <th>{{ __('messages.receipt_number') }}:</th>
                            <td>{{ $payment->receipt_number }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-sticky-note me-2"></i>{{ __('messages.notes') }}</h5>
                    <p>{{ $payment->notes ?? '-' }}</p>
                    
                    @if($payment->invoice)
                    <hr class="my-3">
                    <h6 class="mb-2">{{ __('messages.invoice_details') }}</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>{{ __('messages.total_amount') }}:</th>
                            <td>{{ number_format($payment->invoice->total_amount, 2) }} {{ __('messages.currency') ?? 'EGP' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.final_amount') }}:</th>
                            <td><strong>{{ number_format($payment->invoice->final_amount, 2) }} {{ __('messages.currency') ?? 'EGP' }}</strong></td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.status') }}:</th>
                            <td>
                                @if($payment->invoice->status === 'paid')
                                    <span class="badge bg-success">{{ __('messages.paid') }}</span>
                                @elseif($payment->invoice->status === 'partial')
                                    <span class="badge bg-warning">{{ __('messages.partial') }}</span>
                                @elseif($payment->invoice->status === 'cancelled')
                                    <span class="badge bg-danger">{{ __('messages.cancelled') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('messages.pending') }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>

            @can('update', $payment)
            <hr class="my-4">
            <div class="text-end">
                <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.edit') }}
                </a>
                @can('delete', $payment)
                <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="d-inline">
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

