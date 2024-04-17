<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
			'shipping_address' => ['array', 'required'],
			'user_id' => ['integer', 'exists:users,id', 'required'],
			'status' => ['string', 'max:255', 'required'],
			'total_amount' => ['integer', 'required'],
			'session_id' => ['string', 'max:255', 'required'],
        ];
    }
}
