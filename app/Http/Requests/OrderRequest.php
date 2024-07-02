<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'payment_method' => 'required',
            'payment_gateway' => 'required_if:payment_method,online',
        ];
    }

    public function attributes(): array
    {
        return [
            'payment_method' => __('Payment Method'),
            'payment_gateway' => __('Payment Gateway'),
        ];
    }
}
