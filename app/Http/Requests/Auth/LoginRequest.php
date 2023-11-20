<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => [
                'bail',
                'required',
                'string',
            ],
            'password' => [
                'bail',
                'required',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required'   => ':attribute bắt buộc phải điền!',
            'string'   => ':attribute phải là một chuỗi',
        ];
    }

    public function attributes(): array
    {
        return [
            'email'        => 'Email',
            'password' => 'Mật khẩu',
        ];
    }
}
