@extends('layouts.app')

@section('title', __('messages.add_invoice'))

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
        background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-file-invoice me-2"></i>{{ __('messages.add_invoice') }}</h2>
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
            <form action="{{ route('admin.invoices.store') }}" method="POST" id="invoiceForm">
                @csrf

                <div class="row">
                    <div class="col-md-4">
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

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="appointment_id" class="form-label">{{ __('messages.appointment') }}</label>
                            <select class="form-select @error('appointment_id') is-invalid @enderror" id="appointment_id" name="appointment_id">
                                <option value="">{{ __('messages.select') }}</option>
                                @foreach($appointments as $appointment)
                                    <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                        {{ $appointment->appointment_date ? (\Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d')) : '' }} - {{ $appointment->patient->name ?? '' }}
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
                                <option value="">{{ __('messages.select') }}</option>
                                @foreach($reports as $report)
                                    <option value="{{ $report->id }}" {{ old('report_id') == $report->id ? 'selected' : '' }}>
                                        {{ $report->date ? (\Carbon\Carbon::parse($report->date)->format('Y-m-d')) : '' }} - {{ $report->patient->name ?? '' }}
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
                            <input type="date" class="form-control @error('issue_date') is-invalid @enderror" id="issue_date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required>
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
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
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
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
                                <option value="partial" {{ old('status') == 'partial' ? 'selected' : '' }}>{{ __('messages.partial') }}</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3"><i class="fas fa-list me-2"></i>{{ __('messages.invoice_items') }}</h5>
                
                <div id="items-container">
                    <div class="item-row" data-index="0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.item_type') }} <span class="text-danger">*</span></label>
                                    <select class="form-select item-type" name="items[0][item_type]" required>
                                        <option value="">{{ __('messages.select') }}</option>
                                        <option value="consultation">{{ __('messages.consultation') }}</option>
                                        <option value="medication">{{ __('messages.medication') }}</option>
                                        <option value="test">{{ __('messages.test') }}</option>
                                        <option value="other">{{ __('messages.other') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.item_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control item-name" name="items[0][item_name]" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.quantity') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control item-quantity" name="items[0][quantity]" value="1" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.unit_price') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control item-unit-price" name="items[0][unit_price]" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.total_price') }}</label>
                                    <input type="text" class="form-control item-total-price" readonly>
                                    <input type="hidden" class="item-total-price-hidden" name="items[0][total_price]" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.description') }}</label>
                            <textarea class="form-control" name="items[0][description]" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary mb-3" id="add-item-btn">
                    <i class="fas fa-plus me-2"></i>{{ __('messages.add_item') }}
                </button>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ __('messages.notes') }}</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="summary-card">
                            <h6 class="mb-3"><i class="fas fa-calculator me-2"></i>{{ __('messages.summary') }}</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('messages.total_amount') }}:</span>
                                <strong id="summary-total">0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('messages.discount') }}:</span>
                                <input type="number" class="form-control form-control-sm d-inline-block w-auto text-end" id="discount" name="discount" value="{{ old('discount', 0) }}" step="0.01" min="0" style="max-width: 120px;">
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('messages.tax') }}:</span>
                                <input type="number" class="form-control form-control-sm d-inline-block w-auto text-end" id="tax" name="tax" value="{{ old('tax', 0) }}" step="0.01" min="0" style="max-width: 120px;">
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>{{ __('messages.final_amount') }}:</strong>
                                <strong id="summary-final">0.00</strong>
                            </div>
                            <input type="hidden" id="total_amount" name="total_amount" value="0">
                            <input type="hidden" id="final_amount" name="final_amount" value="0">
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-submit" id="submitBtn">
                        <i class="fas fa-save me-2"></i>{{ __('messages.create') }}
                    </button>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>{{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
            
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = 1;
    
    // Calculate total price for an item
    function calculateItemTotal(itemRow) {
        const quantity = parseFloat(itemRow.querySelector('.item-quantity').value) || 0;
        const unitPrice = parseFloat(itemRow.querySelector('.item-unit-price').value) || 0;
        const total = quantity * unitPrice;
        itemRow.querySelector('.item-total-price').value = total.toFixed(2);
        
        // Update hidden total_price input
        const totalPriceInput = itemRow.querySelector('.item-total-price-hidden');
        if (totalPriceInput) {
            totalPriceInput.value = total.toFixed(2);
        }
        
        calculateSummary();
    }
    
    // Calculate invoice summary
    function calculateSummary() {
        let total = 0;
        document.querySelectorAll('.item-total-price').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const tax = parseFloat(document.getElementById('tax').value) || 0;
        const final = total - discount + tax;
        
        document.getElementById('summary-total').textContent = total.toFixed(2);
        document.getElementById('summary-final').textContent = final.toFixed(2);
        document.getElementById('total_amount').value = total.toFixed(2);
        document.getElementById('final_amount').value = final.toFixed(2);
    }
    
    // Add event listeners for calculation
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate on quantity/price change
        document.querySelectorAll('.item-quantity, .item-unit-price').forEach(input => {
            input.addEventListener('input', function() {
                calculateItemTotal(this.closest('.item-row'));
            });
        });
        
        // Calculate on discount/tax change
        document.getElementById('discount').addEventListener('input', calculateSummary);
        document.getElementById('tax').addEventListener('input', calculateSummary);
        
        // Initial calculation
        calculateSummary();
        
        // Validate form before submit
        document.getElementById('invoiceForm').addEventListener('submit', function(e) {
            // Ensure all total_price fields are updated
            document.querySelectorAll('.item-row').forEach(itemRow => {
                calculateItemTotal(itemRow);
            });
            
            // Validate that at least one item has values
            const hasValidItems = Array.from(document.querySelectorAll('.item-name')).some(input => input.value.trim() !== '');
            if (!hasValidItems) {
                e.preventDefault();
                alert('{{ __("messages.please_add_at_least_one_item") }}');
                return false;
            }
            
            // Validate total amount
            const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
            if (totalAmount <= 0) {
                e.preventDefault();
                alert('{{ __("messages.total_amount_must_be_greater_than_zero") }}');
                return false;
            }
        });
    });
    
    // Add new item
    document.getElementById('add-item-btn').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const newItem = container.firstElementChild.cloneNode(true);
        
        newItem.setAttribute('data-index', itemIndex);
        newItem.querySelectorAll('input, select, textarea').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[0\]/, '[' + itemIndex + ']'));
                if (input.type !== 'hidden' && !input.classList.contains('item-total-price')) {
                    input.value = '';
                }
            }
            // Reset total price fields
            if (input.classList.contains('item-total-price')) {
                input.value = '';
            }
            if (input.classList.contains('item-total-price-hidden')) {
                input.value = '0';
            }
        });
        
        // Add remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-remove-item';
        removeBtn.innerHTML = '<i class="fas fa-trash me-1"></i>{{ __("messages.remove") }}';
        removeBtn.onclick = function() {
            newItem.remove();
            calculateSummary();
        };
        newItem.appendChild(removeBtn);
        
        // Add event listeners for new item
        newItem.querySelectorAll('.item-quantity, .item-unit-price').forEach(input => {
            input.addEventListener('input', function() {
                calculateItemTotal(newItem);
            });
        });
        
        container.appendChild(newItem);
        itemIndex++;
    });
</script>
@endpush
@endsection
