<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => ['string', 'max:255', 'required'],
            'description' => ['string', 'required'],
            'price' => ['integer', 'required'],
            'is_active' => ['string', 'max:255', 'nullable'],
            'cover' => ['file', 'image', 'required'],
        ];
    }
}
