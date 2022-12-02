<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Bookmark;
use App\Notifications\UserActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class BookmarkController extends Controller
{
    ////////////////********* this methods have been tested => OK!

    public function save(Request $request)
    {
        $bookmark = Bookmark::create([
            'product_id' => $request->product,
            'user_id' => $request->user
        ]);

        //create notification
        $data = ['action'=>'افزودن محصول به علاقمندیها'];
        $admin = Admin::query()->first();
        Notification::send($admin,new UserActions($data));
        //end

        return response()->json([
            'msg' => $bookmark
        ]);
    }

    //can be used in both panel admin and store
    public function show($id)
    {
        $products = Bookmark::with(['product'])->where('user_id', $id)->orderByDesc('id')->get();
        return response()->json([
            'products' => $products
        ]);
    }

    public function remove(Request $request)
    {
        return Bookmark::where([
            'id' => $request->id
        ])->delete();
    }

    //can be used in panel admin
    public function showByProduct($product)
    {
        return Bookmark::with('user')->where('product_id', $product)->orderByDesc('id')->get();
    }
}
