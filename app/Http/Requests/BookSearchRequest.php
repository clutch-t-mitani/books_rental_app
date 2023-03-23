<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookSearchRequest extends FormRequest
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
            'category_id' => 'numeric',
            'rental_status' => 'numeric|max:4',
        ];
    }

    public function messages()
    {
        return [
            'category_id.numeric' => '正しく操作されませんでした。再度操作しくださいa。',
            'rental_status.numeric' => '正しく操作されませんでした。再度操作しくださいs。',
            'rental_status.min' => '正しく操作されませんでした。再度操作しくださいd。',
            'rental_status.max' => '正しく操作されませんでした。再度操作しくださいf。',
        ];
    }
}
