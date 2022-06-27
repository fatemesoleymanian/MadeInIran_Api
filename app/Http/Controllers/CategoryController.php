<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    ////////////////********* this methods have been tested => OK!
    public function save(Request $request)
    {
        Validator::validate(
            $request->all(),
            [

                'name' => "bail|required|string",
                'iconImage' => "string",
                'department_id' => "bail|required|integer",
                'metaDescription' => 'bail|required|max:100',
                'metaKeyword' => 'bail|required|max:20',
                'pageTitle' => 'required|max:100',
            ],
            [
                'department_id.integer' => 'لطفا دپارتمان را انتخاب کنید.',
                'department_id.required' => 'لطفا دپارتمان را انتخاب کنید.',
                'name.required' => 'لطفا نام دسته بندی را وارد کنید!',
                'name.string' => 'لطفا نام دسته بندی را به درستی وارد کنید!',
                'iconImage.string' => 'لطفا آدرس فایل را به درستی وارد کنید!',
                'metaDescription.required' => 'لطفا توضیحات متا را به درستی وارد کنید!',
                'metaDescription.max' => 'حداکثر تعداد حروف 100 حرف میباشد!',
                'metaKeyword.required' => 'لطفا کلمه کلیدی متا را به درستی وارد کنید!',
                'metaKeyword.max' => 'حداکثر تعداد حروف 20 حرف میباشد!',
                'pageTitle.required' => 'لطفا تیتر صفحه را به درستی وارد کنید!',
                'pageTitle.max' => 'حداکثر تعداد حروف 100 حرف میباشد!',
            ]
        );
        $category = Category::create([
            'name' => $request->name,
            'iconImage' => $request->iconImage,
            'metaDescription' => $request->metaDescription,
            'metaKeyword' => $request->metaKeyword,
            'pageTitle' => $request->pageTitle,
            'department_id' => $request->department_id,
        ]);
        if ($category) {
            return response()->json([
                'msg' => Lang::get('messages.success', ['attribute' => 'دسته بندی']),
                'department' => $category
            ]);
        }
        return response()->json([
            'errors' => Lang::get('messages.fail', ['attribute' => 'دسته بندی'])
        ]);
    }
    public function update(Request $request)
    {
        Validator::validate(
            $request->all(),
            [

                'name' => "bail|required|string",
                'iconImage' => "string",
                'department_id' => "bail|required|integer",
                'metaDescription' => 'bail|required|max:100',
                'metaKeyword' => 'bail|required|max:20',
                'pageTitle' => 'required|max:100',
            ],
            [
                'department_id.integer' => 'لطفا دپارتمان را انتخاب کنید.',
                'department_id.required' => 'لطفا دپارتمان را انتخاب کنید.',
                'name.required' => 'لطفا نام دسته بندی را وارد کنید!',
                'name.string' => 'لطفا نام دسته بندی را به درستی وارد کنید!',
                'iconImage.string' => 'لطفا آدرس فایل را به درستی وارد کنید!',
                'metaDescription.required' => 'لطفا توضیحات متا را به درستی وارد کنید!',
                'metaDescription.max' => 'حداکثر تعداد حروف 100 حرف میباشد!',
                'metaKeyword.required' => 'لطفا کلمه کلیدی متا را به درستی وارد کنید!',
                'metaKeyword.max' => 'حداکثر تعداد حروف 20 حرف میباشد!',
                'pageTitle.required' => 'لطفا تیتر صفحه را به درستی وارد کنید!',
                'pageTitle.max' => 'حداکثر تعداد حروف 100 حرف میباشد!',
            ]
        );
        $category = Category::where('id', $request->id)->update([
            'name' => $request->name,
            'iconImage' => $request->iconImage,
            'metaDescription' => $request->metaDescription,
            'metaKeyword' => $request->metaKeyword,
            'pageTitle' => $request->pageTitle,
            'department_id' => $request->department_id,
        ]);
        if ($category) {
            return response()->json([
                'msg' => Lang::get('messages.success', ['attribute' => 'دسته بندی']),
                'department' => $category
            ]);
        }
        return response()->json([
            'errors' => Lang::get('messages.fail', ['attribute' => 'دسته بندی'])
        ]);
    }
    public function destroy(Request $id)
    {
        if (!$id->id) {
            return response()->json([
                'errors' => Lang::get('messages.nochoosen')
            ], 401);
        }
        $img = Category::where('id', $id->id)->get('iconImage');
        $imgg = $img[0]->iconImage;
        $upload = new Upload();
        $upload->handydelete($imgg);
        return Category::where('id', $id->id)->delete();
    }
    public function showAll()
    {
        return Category::with(['department', 'product'])->orderByDesc('id')->get();
    }
    public function showAllPagi()
    {
        return Category::with(['department', 'product'])->orderByDesc('id')->get();
    }
    public function showOne($id)
    {
        return Category::where('id', $id)->first();
    }
    public function showOneWithDepartment($id)
    {
        return Category::with(['department'])->where('id', $id)->first();
    }
    public function showAllWithDepartment(Request $request)
    {
        return Category::with(['department'])->orderByDesc('id')->paginate(10);
    }
    public function showOneWithProduct($id)
    {
        return Category::with(['product'])->where('id', $id)->first();
    }
    public function showAllWithProduct()
    {
        return Category::with(['product'])->orderByDesc('id')->paginate(10);
    }
}
