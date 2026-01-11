<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255',
            'remember' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => __('messages.email') . ' ' . __('messages.is_required'),
            'email.email' => __('messages.email') . ' ' . __('messages.must_be_valid'),
            'password.required' => __('messages.password') . ' ' . __('messages.is_required'),
            'password.min' => __('messages.password_min_8_chars'),
        ];
    }
}



