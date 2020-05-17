<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RekeningRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bank_code' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required',
            'account_owner' => 'required',
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
            'bank_code.required' => 'A bank code is required',
            'bank_name.required' => 'A bank name is required',
            'account_number.required' => 'A account number is required',
            'account_owner.required' => 'A account owner is required',
        ];
    }
}
