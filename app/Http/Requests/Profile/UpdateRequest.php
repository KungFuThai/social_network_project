<?php

namespace App\Http\Requests\Profile;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
                'image',
            ],
            'cover_image' => [
                'bail',
                'sometimes',
                'image',
            ],
            'gender' => [
                'bail',
                'sometimes',
                'boolean',
            ],
            'birth_date' => [
                'bail',
                'nullable',
                'date',
                'before:today',
            ],
            'phone' => [
                'bail',
                'required',
                'numeric',
                'regex:/(0)[0-9]{9}/',
                'digits:10',
                Rule::unique(User::class)->ignore(auth()->user()->id),
            ],
            'address' => [
                'bail',
                'sometimes',
                'string',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'last_name'      => 'Họ và lót',
            'first_name'      => 'Tên',
            'phone'      => 'Số điện thoại',
            'address'      => 'Địa chỉ',
            'birth_date'      => 'Ngày sinh',
            'avatar'      => 'Ảnh đại diện',
            'cover_image'      => 'Ảnh bìa',
            'gender'      => 'Giới tính',
        ];
    }

    public function messages(): array
    {
        return [
            'unique'     => ':attribute đã được sử dụng bởi một tài khoản khác',
            'date'     => ':attribute phải là thời gian dd/mm/yyyy',
            'required'   => ':attribute bắt buộc phải điền!',
            'string'   => ':attribute phải là một chuỗi',
            'numeric'   => ':attribute phải là số',
            'regex'   => ':attribute phải đúng định dạng bắt đầu bằng số 0 và đủ 10 số',
            'digits'   => ':attribute phải đủ 10 số',
            'file'   => ':attribute phải là tập tin',
            'image'   => ':attribute phải là ảnh',
        ];
    }
}
