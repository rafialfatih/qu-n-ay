<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => [
                'required',
                'min:3',
                'max:30',
                'string',
            ],
            'username' => [
                'required',
                'min:6',
                'max:20',
                'regex:/^[a-zA-Z0-9][\w\.]+[a-zA-Z0-9]$/',
                Rule::unique('users', 'username')->ignore($this->user),
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
        ];
    }
}
