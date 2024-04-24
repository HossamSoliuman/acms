<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingRequest extends FormRequest
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
            'start_at' => ['date_format:Y-m-d H:i:s', 'nullable'],
            'user_id' => ['integer', 'exists:users,id', 'nullable'],
            'eng_id' => ['integer', 'exists:users,id', 'nullable'],
            'url' => ['string', 'max:255', 'nullable'],
            'rating' => ['integer', 'nullable'],
            'status' => ['string', 'max:255', 'nullable'],
        ];
    }
}
