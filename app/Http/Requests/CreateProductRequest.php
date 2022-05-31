<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name' => "bail|required|string",
            'image' => "bail|required|string",
            'description_excerpt' => "bail|required|string",
            'description' => "bail|required",
            'category_id' => "bail|required|integer",
            'metaDescription' => 'bail|required|max:100',
            'metaKeyword' => 'bail|required|max:120',
            'pageTitle' => 'required|max:100',
            'states' => 'required',
            'costs' => 'required',
            'tags' => 'required',
            'slug' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'لطفا نام محصول را وارد کنید!',
            'tags.required' => 'لطفا حداقل یک تگ برای محصول وارد کنید!',
            'slug.required' => 'لطفا نشانک محصول وارد کنید!',
            'description_excerpt.required' => 'لطفا چکیده توضیحات محصول را وارد کنید!',
            'description.required' => 'لطفا توضیحات محصول را وارد کنید!',
            'category_id.required' => 'لطفا دسته بندی محصول را وارد کنید!',
            'category_id.integer' => 'لطفا دسته بندی محصول را به درستی وارد کنید!',
            'name.string' => 'لطفا نام محصول را به درستی وارد کنید!',
            'description_excerpt.string' => 'لطفا چکیده توضیحات محصول را به درستی وارد کنید!',
            'image.required' => 'لطفا ادرس فایل را وارد کنید!',
            'image.string' => 'لطفا آدرس فایل را به درستی وارد کنید!',
            'metaDescription.required' => 'لطفا توضیحات متا را به درستی وارد کنید!',
            'metaDescription.max' => 'حداکثر تعداد حروف 100 حرف میباشد!',
            'metaKeyword.required' => 'لطفا کلمه کلیدی متا را به درستی وارد کنید!',
            'metaKeyword.max' => 'حداکثر تعداد حروف 20 حرف میباشد!',
            'pageTitle.required' => 'لطفا تیتر صفحه را به درستی وارد کنید!',
            'states.required' => 'لطفا اطلاعات متغیر محصول را وارد کنید!',
            'costs.required' => 'لطفا اطلاعات متغیر محصول را وارد کنید!',
            'pageTitle.max' => 'حداکثر تعداد حروف 100 حرف میباشد!',
        ];
    }
}
