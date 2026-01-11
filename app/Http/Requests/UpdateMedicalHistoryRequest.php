<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalHistoryRequest extends FormRequest
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
            'record_type' => ['required', 'in:allergy,surgery,chronic_disease,medication,vaccination,other'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'attachments' => ['nullable', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'patient_id' => __('messages.patient'),
            'record_type' => __('messages.record_type'),
            'title' => __('messages.title'),
            'description' => __('messages.description'),
            'date' => __('messages.date'),
            'doctor_id' => __('messages.doctor'),
            'attachments' => __('messages.attachments'),
        ];
    }
}
