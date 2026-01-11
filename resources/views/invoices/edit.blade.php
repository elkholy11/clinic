@extends('layouts.app')

@section('title', __('messages.edit_invoice'))

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
    
    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #f5576c;
        box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.25);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(245, 87, 108, 0.4);
        color: white;
    }
    
    .item-row {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
        position: relative;
    }
    
    .btn-remove-item {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        margin-top: 0.5rem;
    }
    
    .summary-card {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-edit me-2"></i>{{ __('messages.edit_invoice') }}</h2>
            </div>
            <div>
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card form-card">
        <div class="card-body">
            <form action="{{ route('admin.invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">{{ __('messages.patient') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                <option value="">{{ __('messages.select_patient') }}</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $invoice->patient_id) == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="appointment_id" class="form-label">{{ __('messages.appointment') }}</label>
                            <select class="form-select @error('appointment_id') is-invalid @enderror" id="appointment_id" name="appointment_id">
                                <option value="">{{ __('messages.select_appointment') }}</option>
                                @foreach($appointments as $appointment)
                                    <option value="{{ $appointment->id }}" {{ old('appointment_id', $invoice->appointment_id) == $appointment->id ? 'selected' : '' }}>
                                        {{ $appointment->patient->name }} - {{ $appointment->appointment_date ? (\Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d')) : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="report_id" class="form-label">{{ __('messages.report') }}</label>
                            <select class="form-select @error('report_id') is-invalid @enderror" id="report_id" name="report_id">
                                <option value="">{{ __('messages.select_report') }}</option>
                                @foreach($reports as $report)
                                    <option value="{{ $report->id }}" {{ old('report_id', $invoice->report_id) == $report->id ? 'selected' : '' }}>
                                        {{ $report->patient->name }} - {{ $report->date ? (\Carbon\Carbon::parse($report->date)->format('Y-m-d')) : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('report_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="issue_date" class="form-label">{{ __('messages.issue_date') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('issue_date') is-invalid @enderror" id="issue_date" name="issue_date" value="{{ old('issue_date', $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('Y-m-d') : '') }}" required>
                            @error('issue_date')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="due_date" class="form-label">{{ __('messages.due_date') }}</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') : '') }}">
                            @error('due_date')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('messages.status') }}</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="pending" {{ old('status', $invoice->status) == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
                                <option value="partial" {{ old('status', $invoice->status) == 'partial' ? 'selected' : '' }}>{{ __('messages.partial') }}</option>
                                <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">{{ __('messages.notes') }}</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>{{ __('messages.invoice_items') }}</h5>
                    <button type="button" class="btn btn-primary btn-sm" id="add-item-btn">
                        <i class="fas fa-plus me-1"></i>{{ __('messages.add_item') }}
                    </button>
                </div>
                
                <div id="items-container">
                    @foreach($invoice->items as $index => $item)
                    <div class="item-row" data-index="{{ $index }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.item_type') }} <span class="text-danger">*</span></label>
                                    <select class="form-select" name="items[{{ $index }}][item_type]" required>
                                        <option value="consultation" {{ $item->item_type == 'consultation' ? 'selected' : '' }}>{{ __('messages.consultation') }}</option>
                                        <option value="medication" {{ $item->item_type == 'medication' ? 'selected' : '' }}>{{ __('messages.medication') }}</option>
                                        <option value="test" {{ $item->item_type == 'test' ? 'selected' : '' }}>{{ __('messages.test') }}</option>
                                        <option value="other" {{ $item->item_type == 'other' ? 'selected' : '' }}>{{ __('messages.other') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.item_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="items[{{ $index }}][item_name]" value="{{ $item->item_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.quantity') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control quantity-input" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.unit_price') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control unit-price-input" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.total_price') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control total-price-input" name="items[{{ $index }}][total_price]" value="{{ $item->total_price }}" step="0.01" min="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.description') }}</label>
                                    <textarea class="form-control" name="items[{{ $index }}][description]" rows="2">{{ $item->description }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-remove-item w-100" onclick="removeItem(this)">
                                    <i class="fas fa-trash me-1"></i>{{ __('messages.remove') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="summary-card">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="total_amount" class="form-label">{{ __('messages.total_amount') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" step="0.01" min="0" value="{{ old('total_amount', $invoice->total_amount) }}" required readonly>
                                @error('total_amount')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="discount" class="form-label">{{ __('messages.discount') }}</label>
                                <input type="number" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" step="0.01" min="0" value="{{ old('discount', $invoice->discount) }}">
                                @error('discount')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="tax" class="form-label">{{ __('messages.tax') }}</label>
                                <input type="number" class="form-control @error('tax') is-invalid @enderror" id="tax" name="tax" step="0.01" min="0" value="{{ old('tax', $invoice->tax) }}">
                                @error('tax')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="final_amount" class="form-label">{{ __('messages.final_amount') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('final_amount') is-invalid @enderror" id="final_amount" name="final_amount" step="0.01" min="0" value="{{ old('final_amount', $invoice->final_amount) }}" required readonly>
                                @error('final_amount')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-2"></i>{{ __('messages.update') }}
                    </button>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>{{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = {{ $invoice->items->count() }};
    
    function calculateItemTotal(input) {
        const row = input.closest('.item-row');
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;
        const totalPrice = quantity * unitPrice;
        row.querySelector('.total-price-input').value = totalPrice.toFixed(2);
        calculateInvoiceTotal();
    }
    
    function calculateInvoiceTotal() {
        let totalAmount = 0;
        document.querySelectorAll('.total-price-input').forEach(input => {
            totalAmount += parseFloat(input.value) || 0;
        });
        
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const tax = parseFloat(document.getElementById('tax').value) || 0;
        const finalAmount = totalAmount - discount + tax;
        
        document.getElementById('total_amount').value = totalAmount.toFixed(2);
        document.getElementById('final_amount').value = finalAmount.toFixed(2);
    }
    
    document.querySelectorAll('.quantity-input, .unit-price-input').forEach(input => {
        input.addEventListener('input', function() {
            calculateItemTotal(this);
        });
    });
    
    document.getElementById('discount').addEventListener('input', calculateInvoiceTotal);
    document.getElementById('tax').addEventListener('input', calculateInvoiceTotal);
    
    document.getElementById('add-item-btn').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const template = container.firstElementChild.cloneNode(true);
        
        template.setAttribute('data-index', itemIndex);
        template.querySelectorAll('input, select, textarea').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, '[' + itemIndex + ']'));
                if (input.type !== 'number' || input.classList.contains('total-price-input')) {
                    input.value = '';
                } else if (input.classList.contains('quantity-input')) {
                    input.value = 1;
                } else if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            }
        });
        
        const removeBtn = template.querySelector('.btn-remove-item');
        if (removeBtn) {
            removeBtn.onclick = function() {
                template.remove();
                calculateInvoiceTotal();
            };
        }
        
        template.querySelectorAll('.quantity-input, .unit-price-input').forEach(input => {
            input.addEventListener('input', function() {
                calculateItemTotal(this);
            });
        });
        
        container.appendChild(template);
        itemIndex++;
    });
    
    function removeItem(btn) {
        const itemRow = btn.closest('.item-row');
        const container = document.getElementById('items-container');
        
        if (container.querySelectorAll('.item-row').length > 1) {
            itemRow.remove();
            calculateInvoiceTotal();
        }
    }
    
    // Initial calculation
    calculateInvoiceTotal();
</script>
@endpush
@endsection

