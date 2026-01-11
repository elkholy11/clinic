@extends('layouts.app')

@section('title', __('messages.view_invoice'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(245, 87, 108, 0.15);
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
        color: #f5576c;
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
    
    .items-table {
        margin-top: 1rem;
    }
    
    .items-table th {
        background: #f8f9fa;
    }
    
    .summary-box {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 1rem;
    }
</style>
@endpush

@section('content')
@php
    $user = Auth::user();
    $role = \App\Helpers\UserHelper::getUserRole($user);
    $isAdmin = $role['isAdmin'];
    $isDoctor = $role['isDoctor'];
    $invoicesRoute = $isAdmin || $isDoctor ? 'admin.invoices' : 'user.invoices';
@endphp
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-file-invoice me-2"></i>{{ __('messages.view_invoice') }}</h2>
            </div>
            <div>
                <a href="{{ route($invoicesRoute . '.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>{{ __('messages.invoice_information') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('messages.invoice_number') }}:</th>
                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.patient') }}:</th>
                            <td>{{ $invoice->patient->name ?? '-' }}</td>
                        </tr>
                        @if($invoice->appointment)
                        <tr>
                            <th>{{ __('messages.appointment') }}:</th>
                            <td>{{ $invoice->appointment->date }}</td>
                        </tr>
                        @endif
                        @if($invoice->report)
                        <tr>
                            <th>{{ __('messages.report') }}:</th>
                            <td><a href="{{ route($isAdmin || $isDoctor ? 'admin.reports.show' : 'user.reports.show', $invoice->report) }}">{{ __('messages.view_report') }}</a></td>
                        </tr>
                        @endif
                        <tr>
                            <th>{{ __('messages.issue_date') }}:</th>
                            <td>{{ $invoice->issue_date ? (\Carbon\Carbon::parse($invoice->issue_date)->format('Y-m-d')) : '-' }}</td>
                        </tr>
                        @if($invoice->due_date)
                        <tr>
                            <th>{{ __('messages.due_date') }}:</th>
                            <td>{{ $invoice->due_date ? (\Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d')) : '-' }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>{{ __('messages.status') }}:</th>
                            <td>
                                @if($invoice->status === 'paid')
                                    <span class="badge bg-success">{{ __('messages.paid') }}</span>
                                @elseif($invoice->status === 'partial')
                                    <span class="badge bg-warning">{{ __('messages.partial') }}</span>
                                @elseif($invoice->status === 'cancelled')
                                    <span class="badge bg-danger">{{ __('messages.cancelled') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('messages.pending') }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="card-title"><i class="fas fa-calculator me-2"></i>{{ __('messages.financial_summary') }}</h5>
                    <div class="summary-box">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th>{{ __('messages.total_amount') }}:</th>
                                <td class="text-end">{{ number_format($invoice->total_amount, 2) }} {{ __('messages.currency') ?? 'EGP' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.discount') }}:</th>
                                <td class="text-end">- {{ number_format($invoice->discount, 2) }} {{ __('messages.currency') ?? 'EGP' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.tax') }}:</th>
                                <td class="text-end">+ {{ number_format($invoice->tax, 2) }} {{ __('messages.currency') ?? 'EGP' }}</td>
                            </tr>
                            <tr class="border-top">
                                <th><strong>{{ __('messages.final_amount') }}:</strong></th>
                                <td class="text-end"><strong>{{ number_format($invoice->final_amount, 2) }} {{ __('messages.currency') ?? 'EGP' }}</strong></td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.total_paid') }}:</th>
                                <td class="text-end text-success">{{ number_format($invoice->getTotalPaid(), 2) }} {{ __('messages.currency') ?? 'EGP' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.remaining_amount') }}:</th>
                                <td class="text-end text-danger"><strong>{{ number_format($invoice->getRemainingAmount(), 2) }} {{ __('messages.currency') ?? 'EGP' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            @if($invoice->notes)
            <hr class="my-4">
            <h5 class="card-title"><i class="fas fa-sticky-note me-2"></i>{{ __('messages.notes') }}</h5>
            <p>{{ $invoice->notes }}</p>
            @endif

            <hr class="my-4">

            <h5 class="card-title"><i class="fas fa-list me-2"></i>{{ __('messages.invoice_items') }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered items-table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.item_type') }}</th>
                            <th>{{ __('messages.item_name') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.unit_price') }}</th>
                            <th>{{ __('messages.total_price') }}</th>
                            <th>{{ __('messages.description') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->items as $item)
                            <tr>
                                <td><span class="badge bg-info">{{ __('messages.' . $item->item_type) }}</span></td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} {{ __('messages.currency') ?? 'EGP' }}</td>
                                <td><strong>{{ number_format($item->total_price, 2) }} {{ __('messages.currency') ?? 'EGP' }}</strong></td>
                                <td>{{ $item->description ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">{{ __('messages.no_items') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @can('create', App\Models\Payment::class)
            @if($invoice->status != 'paid' && $invoice->status != 'cancelled')
            <hr class="my-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0"><i class="fas fa-money-bill-wave me-2"></i>{{ __('messages.payments') }}</h5>
                <a href="{{ route('admin.payments.create', ['invoice_id' => $invoice->id]) }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>{{ __('messages.add_payment_for_invoice') }}
                </a>
            </div>
            @endif
            @endcan

            @if($invoice->payments->count() > 0)
            <hr class="my-4">
            <h5 class="card-title"><i class="fas fa-money-bill-wave me-2"></i>{{ __('messages.payments') }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('messages.amount') }}</th>
                            <th>{{ __('messages.payment_method') }}</th>
                            <th>{{ __('messages.payment_date') }}</th>
                            <th>{{ __('messages.transaction_id') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $payment)
                            <tr>
                                <td><strong>{{ number_format($payment->amount, 2) }} {{ __('messages.currency') ?? 'EGP' }}</strong></td>
                                <td><span class="badge bg-info">{{ __('messages.' . $payment->payment_method) }}</span></td>
                                <td>{{ $payment->payment_date ? (\Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d')) : '-' }}</td>
                                <td>{{ $payment->transaction_id ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @can('update', $invoice)
            <hr class="my-4">
            <div class="text-end">
                <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.edit') }}
                </a>
                @can('delete', $invoice)
                <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" class="d-inline">
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
