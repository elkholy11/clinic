<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'generic_name' => ['nullable', 'string', 'max:255'],
            'dosage' => ['nullable', 'string', 'max:100'],
            'unit' => ['nullable', 'string', 'max:20'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'expiry_date' => ['nullable', 'date'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'requires_prescription' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('messages.name'),
            'generic_name' => __('messages.generic_name'),
            'dosage' => __('messages.dosage'),
            'unit' => __('messages.unit'),
            'price' => __('messages.price'),
            'stock_quantity' => __('messages.stock_quantity'),
            'expiry_date' => __('messages.expiry_date'),
            'manufacturer' => __('messages.manufacturer'),
            'description' => __('messages.description'),
            'category' => __('messages.category'),
            'requires_prescription' => __('messages.requires_prescription'),
            'is_active' => __('messages.is_active'),
        ];
    }
}
