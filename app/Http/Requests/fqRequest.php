<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class fqRequest extends FormRequest
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
            'question' => 'bail|required',
            'answer' => 'bail|required',
            'product' => 'bail|required',
        ];
    }
    public function messages()
    {
        return [
            'question.required' => 'پرسش الزامیست.',
            'answer.required' => 'پاسخ الزامیست.',
            'product.required' => 'حداقل یک محصول مرتبط با پرسش الزامیست.',

        ];
    }
}
