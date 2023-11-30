<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
                'email',
                Rule::unique(User::class, 'email'),
            ],
            'password' => [
                'bail',
                'required',
                'string',
            ],
            'last_name' => [
                'bail',
                'required',
                'string',
            ],
            'first_name' => [
                'bail',
                'required',
                'string',
            ],
            'avatar' => [
                'bail',
                'sometimes',
                'file',
                'image',
            ],
            'phone' => [
                'bail',
                'required',
                'string',
                'numeric',
                'regex:/(0)[0-9]{9}/',
                'digits:10',
                Rule::unique(User::class, 'phone'),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'last_name'      => 'Họ và lót',
            'first_name'      => 'Tên',
            'phone'      => 'Số điện thoại',
            'email'      => 'Email',
            'password'      => 'Mật khẩu',
        ];
    }

    public function messages(): array
    {
        return [
            'unique'     => ':attribute đã được sử dụng bởi một tài khoản khác',
            'required'   => ':attribute bắt buộc phải điền!',
            'string'   => ':attribute phải là một chuỗi',
            'numeric'   => ':attribute phải là số',
            'regex'   => ':attribute phải đúng định dạng bắt đầu bằng số 0',
            'digits'   => ':attribute phải đủ 10 số',
        ];
    }
}
