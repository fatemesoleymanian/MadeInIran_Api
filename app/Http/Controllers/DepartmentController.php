<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    ////////////////********* this methods have been tested => OK!
    public function save(Request $request)
    {
        Validator::validate(
            $request->all(),
            [

                'name' => "required|string",
                'metaDescription' => 'required|max:100',
                'metaKeyword' => 'required|max:20',
                'pageTitle' => 'required|max:100',
            ],
            [
                'name.required' => 'لطفا نام دپارتمان را وارد کنید!',
                'name.string' => 'لطفا نام دپارتمان را به درستی وارد کنید!',
                'metaDescription.required' => 'لطفا توضیحات متا را به درستی وارد کنید!',
                'metaDescription.max' => 'حداکثر تعداد حروف 100 حرف میباشد!',
                'metaKeyword.required' => 'لطفا کلمه کلیدی متا را به درستی وارد کنید!',
                'metaKeyword.max' => 'حداکثر تعداد حروف 20 حرف میباشد!',
                'pageTitle.required' => 'لطفا تیتر صفحه را به درستی وارد کنید!',
                'pageTitle.max' => 'حداکثر تعداد حروف 100 حرف میباشد!',
            ]
        );
        $department = Department::create([
            'name' => $request->name,
            'iconImage' => $request->iconImage,
            'metaDescription' => $request->metaDescription,
            'metaKeyword' => $request->metaKeyword,
            'pageTitle' => $request->pageTitle,
        ]);
        if ($department) {
            return response()->json([
                'msg' => Lang::get('messages.success', ['attribute' => 'دپارتمان']),
                'department' => $department
            ]);
        }
        return response()->json([
            'errors' => Lang::get('messages.fail', ['attribute' => 'دپارتمان'])
        ]);
    }
    public function update(Request $request, $id)
    {
        Validator::validate(
            $request->all(),
            [

                'name' => "required|string",
                'iconImage' => "required|string",
                'metaDescription' => 'required|max:100',
                'metaKeyword' => 'required|max:20',
                'pageTitle' => 'required|max:100',
            ],
            [
                'name.required' => 'لطفا نام دپارتمان را وارد کنید!',
                'name.string' => 'لطفا نام دپارتمان را به درستی وارد کنید!',
                'iconImage.required' => 'لطفا ادرس فایل را وارد کنید!',
                'iconImage.string' => 'لطفا آدرس فایل را به درستی وارد کنید!',
                'metaDescription.required' => 'لطفا توضیحات متا را به درستی وارد کنید!',
                'metaDescription.max' => 'حداکثر تعداد حروف 100 حرف میباشد!',
                'metaKeyword.required' => 'لطفا کلمه کلیدی متا را به درستی وارد کنید!',
                'metaKeyword.max' => 'حداکثر تعداد حروف 20 حرف میباشد!',
                'pageTitle.required' => 'لطفا تیتر صفحه را به درستی وارد کنید!',
                'pageTitle.max' => 'حداکثر تعداد حروف 100 حرف میباشد!',
            ]
        );
        $department = Department::where('id', $id)->update([
            'name' => $request->name,
            'iconImage' => $request->iconImage,
            'metaDescription' => $request->metaDescription,
            'metaKeyword' => $request->metaKeyword,
            'pageTitle' => $request->pageTitle,
        ]);
        if ($department) {
            return response()->json([
                'msg' => Lang::get('messages.success', ['attribute' => 'دپارتمان']),
                'department' => $department
            ]);
        }
        return response()->json([
            'errors' => Lang::get('messages.fail', ['attribute' => 'دپارتمان'])
        ]);
    }
    public function destroy(Request $request)
    {
        if (!$request->id) {
            return response()->json([
                'errors' => Lang::get('messages.nochoosen')
            ], 401);
        }
        $img = Department::where('id', $request->id)->get('iconImage');
        $imgg = $img[0]->iconImage;
        $upload = new Upload();
        $upload->handydelete($imgg);
        return Department::where('id', $request->id)->delete();
    }
    public function showAll()
    {
        return Department::orderByDesc('id')->get();
    }
    public function showOne($id)
    {
        return Department::where('id', $id)->first();
    }
    public function showAllWithCategory()
    {
        return Department::with(['category'])->orderByDesc('id')->get();
    }
    public function showOneWithCategory($id)
    {
        return Department::with(['category'])->where('id', $id)->first();
    }
}
