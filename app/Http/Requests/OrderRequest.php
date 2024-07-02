<?php

namespace App\Http\Requests;

use App\Enums\PaymentGateway;
use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

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
        $paymentMethods = implode(',', PaymentMethod::values());
        $paymentGateways = implode(',', PaymentGateway::values());
        return [
            'payment_method' => 'required|in:' . $paymentMethods,
            'payment_gateway' => 'required_if:payment_method,' . PaymentMethod::ONLINE->value . '|in:' . $paymentGateways,
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
