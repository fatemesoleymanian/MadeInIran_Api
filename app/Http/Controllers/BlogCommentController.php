<?php

namespace App\Http\Controllers;

use App\Http\Requests\commentRequest;
use App\Models\Admin;
use App\Models\BlogComment;
use App\Notifications\UserActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class BlogCommentController extends Controller
{
    //store
    public function save(commentRequest $request)
    {
        $request->validated();

        $comment = BlogComment::create([
            'user_id' => $request->user,
            'blog_id' => $request->blog,
            'comment' => $request->comment,
        ]);

        //create notification
        $data = ['action' => 'ایجاد کامنت برای پست وبلاگ'];
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
    public function show()
    {
        return BlogComment::with(['blog', 'user'])->orderByDesc('id')->get();
    }
    //panel
    public function setStatus($id, Request $request)
    {
        $confirm = BlogComment::where('id', $id)->update(array('status' => $request->status));
        if ($confirm) {
            return response()->json([
                'msg' => 'ثبت توسط ادمین.'
            ]);
        } else {
            return response()->json([
                'msg' => 'خطا در ثبت توسط ادمین.'
            ]);
        }
    }
}
