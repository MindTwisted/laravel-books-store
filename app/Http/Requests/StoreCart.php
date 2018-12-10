<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCart extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $userId = auth()->user()->id;

        return [
            'book_id' => [
                "required", 
                "exists:books,id",
                Rule::unique('cart', 'book_id')->where(function($query) use ($userId) {
                    $query->where('user_id', $userId);
                }),
            ],
            'count' => "required|integer|min:1",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'book_id.unique' => 'Book is already in cart.',
        ];
    }
}
