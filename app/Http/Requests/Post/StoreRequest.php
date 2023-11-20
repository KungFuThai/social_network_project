<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'content'    => [
                'bail',
                'required',
                'string',
            ],
            'image' => [
                'bail',
                'sometimes',
                'file',
                'image',
            ],
            'status' => [
                'bail',
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required'   => ':attribute bắt buộc phải điền!',
            'string'   => ':attribute phải là một chuỗi',
            'file'   => ':attribute phải là file',
            'image'   => ':attribute phải là ảnh',
        ];
    }

    public function attributes(): array
    {
        return [
            'content' => 'Nội dung',
            'image' => 'Ảnh',
            'status' => 'Trạng thái',
        ];
    }
}
