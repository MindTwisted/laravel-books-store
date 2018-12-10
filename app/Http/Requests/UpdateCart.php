<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCart extends FormRequest
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
        $id = $this->route('cart')->id;

        return [
            'book_id' => [
                "required", 
                "exists:books,id",
                Rule::exists('cart', 'book_id')->where(function($query) use ($userId, $id) {
                    $query->where('user_id', $userId)
                        ->where('id', $id);
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
            'book_id.exists' => "Book doesn't exist in cart.",
        ];
    }
}
