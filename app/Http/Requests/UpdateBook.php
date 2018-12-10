<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlphaNumSpaces;

class UpdateBook extends FormRequest
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
        $id = $this->route('book')->id;

        return [
            'title' => ["required", "unique:books,title,$id", new AlphaNumSpaces],
            'description' => "required|min:20",
            'price' => "required|numeric|min:1",
            'discount' => "required|numeric|min:0|max:50"
        ];
    }
}
