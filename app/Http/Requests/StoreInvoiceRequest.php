<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'patient_id' => ['required', 'exists:patients,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'report_id' => ['nullable', 'exists:reports,id'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'final_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:pending,paid,cancelled,partial'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'payment_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'string', 'max:100'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.total_price' => ['required', 'numeric', 'min:0'],
            'items.*.description' => ['nullable', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'patient_id' => __('messages.patient'),
            'appointment_id' => __('messages.appointment'),
            'report_id' => __('messages.report'),
            'total_amount' => __('messages.total_amount'),
            'discount' => __('messages.discount'),
            'tax' => __('messages.tax'),
            'final_amount' => __('messages.final_amount'),
            'status' => __('messages.status'),
            'issue_date' => __('messages.issue_date'),
            'due_date' => __('messages.due_date'),
            'payment_date' => __('messages.payment_date'),
            'notes' => __('messages.notes'),
            'items' => __('messages.items'),
        ];
    }
}
