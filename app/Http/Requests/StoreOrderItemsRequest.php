<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderItemsRequest extends FormRequest
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
			'order_id' => ['integer', 'exists:orders,id', 'required'],
			'product_id' => ['integer', 'exists:products,id', 'required'],
			'quantity' => ['integer', 'required'],
        ];
    }
}
