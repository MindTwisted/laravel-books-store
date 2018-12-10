<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteCart extends FormRequest
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
        return [
            
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $id = $this->route('cart')->id;
        $user = auth()->user();
        $cart = $user->cart()->find($id);

        $validator->after(function ($validator) use ($cart) {
            if (!$cart) {
                $validator->errors()->add('ID', "Invalid cart id.");
            }
        });
    }
}
