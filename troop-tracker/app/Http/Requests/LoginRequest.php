<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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

    public function rules()
    {
        return [
            'username' => [
                'required',
                Rule::exists('troopers', 'forum_id'),
            ],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username is required.',
            'username.exists' => 'This username does not exist in our records - do you need to setup your account?',
            'password.required' => 'Password is required.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'username' => $this->input('username'),
            'remember_me' => $this->input('remember_me') === 'Y',
        ]);
    }
}
