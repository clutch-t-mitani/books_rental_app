<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterBookRequest extends FormRequest
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
            'name' => 'required',
            'author' => 'required',
            'id' => 'numeric',
            'category_id' => 'array|numeric',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'タイトルは必須項目です。',
            'author.required' => '作者は必須項目です。',
            'id.numeric' => '正しく操作されませんでした。再度操作しくださいa。',
            'category_id.numeric' => '正しく操作されませんでした。再度操作しください。',
            'category_id.array' => '正しく操作されませんでした。再度操作しください。',
        ];
    }
}
