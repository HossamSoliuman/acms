<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemsRequest extends FormRequest
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
			'order_id' => ['integer', 'exists:orders,id', 'nullable'],
			'product_id' => ['integer', 'exists:products,id', 'nullable'],
			'quantity' => ['integer', 'nullable'],
        ];
    }
}
