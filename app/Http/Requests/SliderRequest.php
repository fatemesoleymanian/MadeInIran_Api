<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            'image' => "required|image|mimes:jpg,png,jpeg,gif,svg|
            dimensions:min_width=400,min_height=200,max_width=1920,max_height=800",
        ];
    }
    public function messages()
    {
        return [
            'image.required' => 'عکس الزامیست.',
            'image.image' => 'لطفا عکس را انتخاب کنید.',
            'image.mimes' => 'فرمت های قابل قبول عکس : png,jpeg,gif,svg',
            'image.dimensions' => 'حداکثر ابعاد تصویر : 1920 X 800',
        ];
    }
}
