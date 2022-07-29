<?php

namespace App\Http\Controllers;

use App\Http\Requests\commentRequest;
use App\Http\Requests\RequesForRepresentation;
use App\Models\Catalog;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\ExceptionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCommentController extends Controller
{
    //store
//    public function save(commentRequest $request)
//    {
//        $request->validated();
//        $comment = ProductComment::create([
//            'user_id' => $request->user,
//            'product_id' => $request->product,
//            'comment' => $request->comment,
//        ]);
//        if ($comment) return response()->json([
//            'msg' => 'دیدگاه شما ثبت شد و پس از تایید ادمین قابل مشاهده می باشد.'
//        ]);
//        else return response()->json([
//            'msg' => 'خطایی در ثبت دیدگاه رخ داد!'
//        ]);
//    }
//
//    //panel
//    public function confirmComment($id, Request $request)
//    {
//
//        $confirm = ProductComment::where('id', $id)->update(array('status' => $request->status));
//        if ($confirm) {
//            return response()->json([
//                'msg' => 'ثبت توسط ادمین.'
//            ]);
//        }
//        else {
//            return response()->json([
//                'msg' => 'خطا در ثبت توسط ادمین.'
//            ]);
//        }
//    }
//
//    //panel
    public function showRequests()
    {
        return Catalog::orderByDesc('id')->get();
    }

    //store
    public function save(RequesForRepresentation $request)
    {
        $request->validated();
        $res = Catalog::create([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'age' => $request->age,
            'education' => $request->education,
            'course' => $request->course,
            'work_experience' => $request->work_experience,
            'job' => $request->job,
            'selected_package' => $request->selected_package,
            'product' => $request->product,
            'reasons' => $request->reasons,
            'experts' => $request->experts
        ]);

        try {
            Mail::send('mail.catalog_form', [
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'age' => $request->age,
                'education' => $request->education,
                'course' => $request->course,
                'work_experience' => $request->work_experience,
                'job' => $request->job,
                'selected_package' => $request->selected_package,
                'reasons' => $request->reasons,
                'experts' => $request->experts,
                'created_at' => $res->created_at
            ], function ($message) use ($request){
                $message->to('pedichbiz@gmail.com');
                $message->subject('  فرم درخواست نمایندگی  ' . $request->product);
            });
        }
        catch (ExceptionInterface $e) {
            return response()->json([
                'msg' => $e
            ]);
        }
        if ($res) {
            return response()->json([
                'msg' => 'اطلاعات با موفقیت ثبت شد!'
            ]);
        }
        else {
            return response()->json([
                'msg' => $res
            ]);
        }

    }
}
