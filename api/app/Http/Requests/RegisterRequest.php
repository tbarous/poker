<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:players',
            'password' => 'required|confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A name is required',
            'name.max:255' => 'Name is too long',
            'email.required' => 'An email is required',
            'email.unique' => 'Email exists',
            'email.email' => 'Not valid email',
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password is not confirmed'
        ];
    }
}
