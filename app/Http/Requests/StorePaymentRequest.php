<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'exists:invoices,id'],
            'patient_id' => ['required', 'exists:patients,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,card,bank_transfer,check,online'],
            'payment_date' => ['required', 'date'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'receipt_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'invoice_id' => __('messages.invoice'),
            'patient_id' => __('messages.patient'),
            'amount' => __('messages.amount'),
            'payment_method' => __('messages.payment_method'),
            'payment_date' => __('messages.payment_date'),
            'transaction_id' => __('messages.transaction_id'),
            'receipt_number' => __('messages.receipt_number'),
            'notes' => __('messages.notes'),
        ];
    }
}
