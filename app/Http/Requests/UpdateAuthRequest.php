<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuthRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'nullable|string|email|max:255|unique:users,email,' . auth()->id(),
            'password' => 'nullable|string|min:8',
            'name' => 'nullable|string|max:255',
        ];
    }
}
