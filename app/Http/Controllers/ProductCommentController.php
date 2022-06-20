<?php

namespace App\Http\Controllers;

use App\Http\Requests\commentRequest;
use App\Models\ProductComment;
use Illuminate\Http\Request;

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
        if ($confirm) return response()->json([
            'msg' => 'ثبت توسط ادمین.'
        ]);
        else {
            return response()->json([
                'msg' => 'خطا در ثبت توسط ادمین.'
            ]);
        }
    }

    //panel
    public function showComments()
    {
        return ProductComment::with(['user', 'product'])->orderByDesc('id')->paginate(10);
    }
}
