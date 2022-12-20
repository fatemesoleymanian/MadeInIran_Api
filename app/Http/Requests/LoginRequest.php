<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => "required",
            'password' => "bail|required|max:16,min:8",
        ];
    }
    public function messages()
    {
        return [
            'username.required' => 'لطفا نام کاربری را وارد کنید!',
            'password.required' => 'لطفا رمز عبور خود را وارد کنید!',
            'password.max' => 'تعداد کاراکتر های رمز عبور حداکثر 16 می باشد.',
            'password.min' => 'تعداد کاراکتر های رمز عبور حداقل 8 می باشد.',
        ];
    }
}
