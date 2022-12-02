<?php

namespace App\Http\Controllers;

use App\Http\Requests\commentRequest;
use App\Http\Requests\RequesForRepresentation;
use App\Models\Admin;
use App\Models\Catalog;
use App\Models\ProductComment;
use App\Notifications\UserActions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mailer\Exception\ExceptionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCommentController extends Controller
{
    //store
    public function save(commentRequest $request)
    {
        $request->validated();
        $comment = ProductComment::create([
            'user_id' => $request->user,
            'product_id' => $request->product,
            'comment' => $request->comment,
        ]);

        $data = ['action' => 'ایجاد کامنت برای محصول'];
        //create notification
        $admin = Admin::query()->first();
        Notification::send($admin, new UserActions($data));
        //end

        if ($comment) return response()->json([
            'msg' => 'دیدگاه شما ثبت شد و پس از تایید ادمین قابل مشاهده می باشد.'
        ]);
        else return response()->json([
            'msg' => 'خطایی در ثبت دیدگاه رخ داد!'
        ]);
    }

    //panel
    public function confirmComment($id, Request $request)
    {

        $confirm = ProductComment::where('id', $id)->update(array('status' => $request->status));
        if ($confirm) {
            return response()->json([
                'msg' => 'ثبت توسط ادمین.'
            ]);
        }
        else {
            return response()->json([
                'msg' => 'خطا در ثبت توسط ادمین.'
            ]);
        }
    }

    //panel
    public function show()
    {
        return ProductComment::with(['product','user'])->orderByDesc('id')->get();
    }

}
