<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function create(Request $request)
    {
        Validator::validate(
            $request->all(),
            [
                'image' => "required",
            ],
            [
                'image.required' => 'لطفا عکسی آپلود کنید!',
            ]
        );
        $slider = Slider::create([
            'image' => $request->image,
            'link' => $request->link,
            'text' => $request->text,
            'sub_title' => $request->sub_title,
            'title' => $request->title,
        ]);
        if ($slider) {
            return response()->json([
                'msg' => Lang::get('messages.success', ['attribute' => 'اسلاید']),
                'slider' => $slider
            ]);
        }
        return response()->json([
            'errors' => Lang::get('messages.fail', ['attribute' => 'اسلاید'])
        ]);
    }
    public function update(Request $request)
    {
        Validator::validate(
            $request->all(),
            [
                'image' => "required",
            ],
            [
                'image.required' => 'لطفا عکسی آپلود کنید!',
            ]
        );


        //update others
        $slider = Slider::where('id', $request->id)->update([
            'image' => $request->image,
            'link' => $request->link,
            'text' => $request->text,
            'sub_title' => $request->sub_title,
            'title' => $request->title,
        ]);
        if ($slider) {
            return response()->json([
                'msg' => 'اسلاید با موفقیت ویرایش شد.',
                'slider' => $slider
            ]);
        }
        return response()->json([
            'errors' => 'خطا در ایجاد اسلاید'
        ]);
    }
    public function delete(Request $request)
    {
        //delete old image
        $img = Slider::where('id', $request->id)->get('image');
        $imgg = $img[0]->image;
        $upload = new Upload();
        $upload->handydelete($imgg);

        return Slider::where('id', $request->id)->delete();
    }
    //for panel
    public function showAll()
    {
        return Slider::orderByDesc('id')->get();
    }
    //home page (no authentication)
    public function showHomeSlider()
    {
        $slides = Cache::remember('slider_home', now()->addHour(1), function () {
            return Slider::orderByDesc('id')->get();
        });
        return $slides;
    }
    public function showOne($id)
    {
        return Slider::where('id', $id)->first();
    }
}
