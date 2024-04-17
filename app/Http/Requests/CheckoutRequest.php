<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'shipping_address' => ['required', 'array'],
            'products' => ['required', 'array', 'min:1'],
            'products.*' => ['array'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'min:1'],

        ];
    }
}
