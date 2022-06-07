<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestimonialRequest;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function save(TestimonialRequest $request)
    {
        $request->validated();
        $testmonial = Testimonial::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'position' => $request->position,
        ]);
        if ($testmonial) return response()->json([
            'msg' => 'با موفقیت ایجاد گردید.',
            'testimonial' => $testmonial
        ], 200);
        else return response()->json([
            'msg' => 'خطا در ثبت نظر.',
        ], 401);
    }
    public function update(TestimonialRequest $request, $id)
    {
        $request->validated();
        $testmonial = Testimonial::where('id', $id)->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'position' => $request->position,
        ]);
        if ($testmonial) return response()->json([
            'msg' => 'با موفقیت ویرایش گردید.',
            'testimonial' => $testmonial
        ], 200);
        else return response()->json([
            'msg' => 'خطا در ویرایش نظر.',
        ], 401);
    }
    public function show()
    {
        return Testimonial::orderByDesc('id')->get();
    }
    public function destroy(Request $request)
    {
        $delete = Testimonial::where('id', $request->id)->delete();
        if ($delete == 1) return response()->json([
            'msg' => 'با موفقیت حذف گردید.',
        ], 200);
        else return response()->json([
            'msg' => 'خطا در حذف نظر.',
        ], 401);
    }
}
