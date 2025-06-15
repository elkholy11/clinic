<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date' => ['required', 'date'],
            'diagnosis' => ['required', 'string'],
            'prescription' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => __('Please select a patient.'),
            'patient_id.exists' => __('The selected patient is invalid.'),
            'doctor_id.required' => __('Please select a doctor.'),
            'doctor_id.exists' => __('The selected doctor is invalid.'),
            'date.required' => __('Please select a date.'),
            'date.date' => __('The date must be a valid date.'),
            'diagnosis.required' => __('Please enter the diagnosis.'),
            'diagnosis.string' => __('The diagnosis must be text.'),
            'prescription.required' => __('Please enter the prescription.'),
            'prescription.string' => __('The prescription must be text.'),
            'notes.string' => __('The notes must be text.'),
        ];
    }
} 