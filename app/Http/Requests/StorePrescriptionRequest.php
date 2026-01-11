<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrescriptionRequest extends FormRequest
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
            'report_id' => ['nullable', 'exists:reports,id'],
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'prescription_date' => ['required', 'date'],
            'status' => ['nullable', 'in:active,completed,cancelled'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medication_id' => ['required', 'exists:medications,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.dosage' => ['nullable', 'string', 'max:100'],
            'items.*.frequency' => ['nullable', 'string', 'max:100'],
            'items.*.duration' => ['nullable', 'integer', 'min:1'],
            'items.*.instructions' => ['nullable', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'report_id' => __('messages.report'),
            'patient_id' => __('messages.patient'),
            'doctor_id' => __('messages.doctor'),
            'prescription_date' => __('messages.prescription_date'),
            'status' => __('messages.status'),
            'notes' => __('messages.notes'),
            'items' => __('messages.items'),
        ];
    }
}
