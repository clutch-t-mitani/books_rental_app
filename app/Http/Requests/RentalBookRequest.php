<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RentalBookRequest extends FormRequest
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
            'book_id' => 'required|array',
            'book_id.*' => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'book_id.required' => '正しく操作されませんでした。再度操作しください。',
            'book_id.array' => '正しく操作されませんでした。再度操作しください。',
            'book_id.numeric' => '正しく操作されませんでした。再度操作しください。',
        ];
    }
}
