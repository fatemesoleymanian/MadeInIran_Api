<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class commentRequest extends FormRequest
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
        return   [
            'comment' => "bail|required|max:20000",
        ];
    }
    public function messages()
    {
        return [
            'comment.required' => 'لطفا دیدگاه خود را راجع به محصول را وارد کنید!',
            'comment.max' => 'تعداد کاراکتر بیش از حد مجاز برای دیدگاه می باشد!',

        ];
    }
}
