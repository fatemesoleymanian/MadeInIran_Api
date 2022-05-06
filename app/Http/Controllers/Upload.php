<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Upload extends Controller
{
    ////////////////********* this methods have been tested => OK!
    public function uploadImage(Request $request)
    {
        Validator::validate($request->all(),[

            'image'=>"required|image|mimes:jpg,png,jpeg,gif,svg",
        ],[
            'image.required'=>'لطفا عکس را وارد کنید!',
            'image.image'=>'لطفا عکس را به درستی وارد کنید!',
            'image.mimes'=>'jpeg,jpg,png,svg میباشد فرمت های قابل قبول برای عکس!',
        ]);
        $picName = time() . '.' . $request->image->extension();
        $request->image->move(public_path($request->location), $picName);
        return response()->json([
            'success'=> 1,
            'file'=>["url"=>"https://api.madein-iran.com/$request->location/$picName"]
//            'file'=>["url"=> Storage::url("$request->location/$picName")]
        ]);
        //age in response ro ngrfti bedun ke anjam nshde
    }
    public function deleteUploaded(Request $request)
    {
        $path = parse_url($request->imageName);
        $remove = File::delete(public_path($path['path']));
        if ($remove)  return response()->json([
            'success'=> 1,
            'msg'=> 'فایل با موفقیت حذف گردید.'
        ]);
        else  return response()->json([
            'success'=> 0,
            'msg'=> 'خطا در حذف فایل'
        ]);

    }
    public function deleteGroupImages(Request $request)
    {
        $images=[];
        $images= $request->imageName;
        foreach ($images as $image) {

            $path = parse_url($image);
            $remove = File::delete(public_path($path['path']));
            if (!$remove) return response()->json([
                'success'=> 0,
                'msg'=> 'خطا در حذف فایلها'
            ]);
        }
        return  response()->json([
            'success'=> 1,
            'msg'=> 'فایلها با موفقیت حذف گردیدند.'
        ]);
    }
    public function handydelete($imageName)
    {
        $path = parse_url($imageName);
        $remove = File::delete(public_path($path['path']));


        if ($remove)  return response()->json([
            'success'=> 1,
            'msg'=> 'فایل با موفقیت حذف گردید.'
        ]);
        else  return response()->json([
            'success'=> 0,
            'msg'=> 'خطا در حذف فایل'
        ]);

    }
}
