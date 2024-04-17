<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
			'shipping_address' => ['array', 'nullable'],
			'user_id' => ['integer', 'exists:users,id', 'nullable'],
			'status' => ['string', 'max:255', 'nullable'],
			'total_amount' => ['integer', 'nullable'],
			'session_id' => ['string', 'max:255', 'nullable'],
        ];
    }
}
