@extends('layouts.app')

@section('title', __('messages.add_payment'))

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
    
    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        color: white;
    }
    
    .invoice-info {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
        border-left: 4px solid #4facfe;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-money-bill-wave me-2"></i>{{ __('messages.add_payment') }}</h2>
            </div>
            <div>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card form-card">
        <div class="card-body">
            <form action="{{ route('admin.payments.store') }}" method="POST" id="paymentForm">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="invoice_id" class="form-label">{{ __('messages.invoice') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('invoice_id') is-invalid @enderror" id="invoice_id" name="invoice_id" required>
                                <option value="">{{ __('messages.select') }}</option>
                                @foreach($invoices as $invoice)
                                    @php
                                        $totalPaid = isset($invoice->total_paid) ? $invoice->total_paid : ($invoice->payments ? $invoice->payments->sum('amount') : 0);
                                    @endphp
                                    <option value="{{ $invoice->id }}" 
                                        data-patient-id="{{ $invoice->patient_id }}"
                                        data-final-amount="{{ $invoice->final_amount }}"
                                        data-total-paid="{{ $totalPaid }}"
                                        {{ old('invoice_id', $selectedInvoice && $selectedInvoice->id == $invoice->id ? $invoice->id : '') == $invoice->id ? 'selected' : '' }}>
                                        {{ $invoice->invoice_number }} - {{ $invoice->patient->name ?? '' }} ({{ number_format($invoice->final_amount, 2) }} {{ __('messages.currency') ?? 'EGP' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('invoice_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <div id="invoice-info" class="invoice-info" style="display: none;">
                                <small>
                                    <strong>{{ __('messages.final_amount') }}:</strong> <span id="final-amount">0</span> {{ __('messages.currency') ?? 'EGP' }}<br>
                                    <strong>{{ __('messages.total_paid') }}:</strong> <span id="total-paid">0</span> {{ __('messages.currency') ?? 'EGP' }}<br>
                                    <strong>{{ __('messages.remaining_amount') }}:</strong> <span id="remaining-amount">0</span> {{ __('messages.currency') ?? 'EGP' }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">{{ __('messages.patient') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                <option value="">{{ __('messages.select_patient') }}</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="amount" class="form-label">{{ __('messages.amount') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">{{ __('messages.payment_method') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>{{ __('messages.cash') }}</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>{{ __('messages.card') }}</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>{{ __('messages.bank_transfer') }}</option>
                                <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>{{ __('messages.other') }}</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">{{ __('messages.payment_date') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="transaction_id" class="form-label">{{ __('messages.transaction_id') }}</label>
                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror" id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}">
                            @error('transaction_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="receipt_number" class="form-label">{{ __('messages.receipt_number') }}</label>
                            <input type="text" class="form-control @error('receipt_number') is-invalid @enderror" id="receipt_number" name="receipt_number" value="{{ old('receipt_number') }}">
                            @error('receipt_number')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">{{ __('messages.notes') }}</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="text-end mt-4">
                    <button type="button" class="btn btn-submit" id="submitBtn" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal">
                        <i class="fas fa-save me-2"></i>{{ __('messages.create') }}
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>{{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirm Payment Modal -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <h5 class="modal-title" id="confirmPaymentModalLabel">
                    <i class="fas fa-check-circle me-2"></i>{{ __('messages.confirm_payment') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>{{ __('messages.payment_confirmation_message') }}
                </div>
                <div class="payment-summary">
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('messages.invoice_number') }}:</th>
                            <td id="modal-invoice-number">-</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.patient') }}:</th>
                            <td id="modal-patient-name">-</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.amount') }}:</th>
                            <td><strong id="modal-amount">0</strong> {{ __('messages.currency') ?? 'EGP' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.payment_method') }}:</th>
                            <td id="modal-payment-method">-</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.payment_date') }}:</th>
                            <td id="modal-payment-date">-</td>
                        </tr>
                        <tr>
                            <th>{{ __('messages.remaining_after_payment') }}:</th>
                            <td><strong id="modal-remaining">0</strong> {{ __('messages.currency') ?? 'EGP' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>{{ __('messages.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="confirmSubmitBtn">
                    <i class="fas fa-check me-2"></i>{{ __('messages.confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const invoiceSelect = document.getElementById('invoice_id');
    const patientSelect = document.getElementById('patient_id');
    const amountInput = document.getElementById('amount');
    const paymentMethodSelect = document.getElementById('payment_method');
    const paymentDateInput = document.getElementById('payment_date');
    const invoiceInfo = document.getElementById('invoice-info');
    const paymentForm = document.getElementById('paymentForm');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));
    let currentRemaining = 0;

    // Initialize on page load if invoice is pre-selected
    document.addEventListener('DOMContentLoaded', function() {
        if (invoiceSelect.value) {
            invoiceSelect.dispatchEvent(new Event('change'));
        }
    });

    // Update invoice info when invoice is selected
    invoiceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const finalAmount = parseFloat(selectedOption.getAttribute('data-final-amount')) || 0;
            const totalPaid = parseFloat(selectedOption.getAttribute('data-total-paid')) || 0;
            currentRemaining = finalAmount - totalPaid;
            
            document.getElementById('final-amount').textContent = finalAmount.toFixed(2);
            document.getElementById('total-paid').textContent = totalPaid.toFixed(2);
            document.getElementById('remaining-amount').textContent = currentRemaining.toFixed(2);
            
            // Auto-fill patient_id
            const patientId = selectedOption.getAttribute('data-patient-id');
            if (patientId) {
                patientSelect.value = patientId;
            }
            
            // Set max amount
            amountInput.setAttribute('max', currentRemaining);
            amountInput.setAttribute('data-invoice-number', selectedOption.text.split(' - ')[0]);
            
            invoiceInfo.style.display = 'block';
        } else {
            invoiceInfo.style.display = 'none';
            amountInput.removeAttribute('max');
        }
    });

    // Validate amount on input
    amountInput.addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        if (amount > currentRemaining) {
            this.setCustomValidity('{{ __("messages.amount_exceeds_remaining") }}');
        } else {
            this.setCustomValidity('');
        }
    });

    // Update modal when submit button is clicked
    document.getElementById('submitBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validate form first
        if (!paymentForm.checkValidity()) {
            paymentForm.reportValidity();
            return;
        }

        const selectedInvoice = invoiceSelect.options[invoiceSelect.selectedIndex];
        const selectedPatient = patientSelect.options[patientSelect.selectedIndex];
        const amount = parseFloat(amountInput.value) || 0;
        const paymentMethod = paymentMethodSelect.options[paymentMethodSelect.selectedIndex].text;
        const paymentDate = paymentDateInput.value;
        const remaining = currentRemaining - amount;

        // Update modal content
        document.getElementById('modal-invoice-number').textContent = selectedInvoice.text.split(' - ')[0];
        document.getElementById('modal-patient-name').textContent = selectedPatient.text;
        document.getElementById('modal-amount').textContent = amount.toFixed(2);
        document.getElementById('modal-payment-method').textContent = paymentMethod;
        document.getElementById('modal-payment-date').textContent = paymentDate;
        document.getElementById('modal-remaining').textContent = remaining.toFixed(2);
        
        if (remaining < 0) {
            document.getElementById('modal-remaining').parentElement.innerHTML = 
                '<strong class="text-danger">' + remaining.toFixed(2) + '</strong> {{ __("messages.currency") ?? "EGP" }} <span class="badge bg-warning">{{ __("messages.exceeds_remaining") }}</span>';
        }
    });

    // Submit form when confirmed
    document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
        paymentForm.submit();
    });
</script>
@endpush
@endsection

