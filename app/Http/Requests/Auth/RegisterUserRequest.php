<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
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
            'username' => ['required', 'min:6', 'max:20', 'regex:/^[a-zA-Z0-9][\w\.]+[a-zA-Z0-9]$/', Rule::unique('users', 'username')],
            'name' => ['required', 'min:3', 'max:30', 'string'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:8', 'max:30'],
        ];
    }
}
