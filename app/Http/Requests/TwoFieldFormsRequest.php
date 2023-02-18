<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TwoFieldFormsRequest extends FormRequest
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
            'full_name' => 'required',
            'phone_number' => 'required|numeric|digits:11',
        ];
    }
    public function messages()
    {
        return [
            'full_name.required' => 'لطفا نام و نام خانوادگی را وارد کنید.',
            'phone_number.required' => 'لطفا شماره تماس را وارد کنید.',
            'phone_number.numeric' => 'لطفا شماره تماس را به درستی وارد کنید.',
            'phone_number.digits' => 'لطفا شماره تماس را به درستی وارد کنید.',

        ];
    }
}
