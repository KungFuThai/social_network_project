<?php

namespace App\Http\Requests\Reaction;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToggleReactionRequest extends FormRequest
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
            'type' => [
                'bail',
                'required',
                'boolean',
            ],
            'post_id' => [
                'bail',
                'required',
                Rule::exists(Post::class,'id'),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'required'   => ':attribute bắt buộc phải điền!',
            'boolean'   => ':attribute chỉ có thể là 0 hoặc 1!',
            'post_id.exists'   => ':attribute không tồn tại hoặc đã bị xoá!',
        ];
    }

    public function attributes(): array
    {
        return [
            'post_id' => 'Bài đăng',
            'type' => 'Loại cảm xúc',
        ];
    }
}
