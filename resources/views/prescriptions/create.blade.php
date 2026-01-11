@extends('layouts.app')

@section('title', __('messages.add_prescription'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.75rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
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
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .item-row {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }
    
    .btn-remove-item {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-prescription me-2"></i>{{ __('messages.add_prescription') }}</h2>
            </div>
            <div>
                <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card form-card">
        <div class="card-body">
            <form action="{{ route('admin.prescriptions.store') }}" method="POST" id="prescriptionForm">
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
                            <label for="doctor_id" class="form-label">{{ __('messages.doctor') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id" required>
                                <option value="">{{ __('messages.select_doctor') }}</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="prescription_date" class="form-label">{{ __('messages.prescription_date') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('prescription_date') is-invalid @enderror" id="prescription_date" name="prescription_date" value="{{ old('prescription_date', date('Y-m-d')) }}" required>
                            @error('prescription_date')
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

                <hr class="my-4">
                <h5 class="mb-3"><i class="fas fa-pills me-2"></i>{{ __('messages.items') }}</h5>
                
                <div id="items-container">
                    <div class="item-row" data-index="0">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.medication') }} <span class="text-danger">*</span></label>
                                    <select class="form-select" name="items[0][medication_id]" required>
                                        <option value="">{{ __('messages.select') }}</option>
                                        @foreach($medications as $medication)
                                            <option value="{{ $medication->id }}">{{ $medication->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.quantity') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="items[0][quantity]" value="1" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.dosage') }}</label>
                                    <input type="text" class="form-control" name="items[0][dosage]">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.frequency') }}</label>
                                    <input type="text" class="form-control" name="items[0][frequency]" placeholder="e.g., 3 times/day">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.duration') }}</label>
                                    <input type="number" class="form-control" name="items[0][duration]" min="1" placeholder="Days">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.instructions') }}</label>
                            <textarea class="form-control" name="items[0][instructions]" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary mb-3" id="add-item-btn">
                    <i class="fas fa-plus me-2"></i>{{ __('messages.add_item') }}
                </button>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-2"></i>{{ __('messages.create') }}
                    </button>
                    <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>{{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = 1;
    
    document.getElementById('add-item-btn').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const newItem = container.firstElementChild.cloneNode(true);
        
        // Update all input names with new index
        newItem.setAttribute('data-index', itemIndex);
        newItem.querySelectorAll('input, select, textarea').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[0\]/, '[' + itemIndex + ']'));
                input.value = '';
            }
        });
        
        // Add remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-remove-item';
        removeBtn.innerHTML = '<i class="fas fa-trash me-1"></i>{{ __("messages.remove") }}';
        removeBtn.onclick = function() {
            newItem.remove();
        };
        newItem.appendChild(removeBtn);
        
        container.appendChild(newItem);
        itemIndex++;
    });
</script>
@endpush
@endsection
