<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    ////////////////********* this methods have been tested => OK!

    public function save(Request $request)
    {
        $bookmark = Bookmark::create([
            'product_id' => $request->product,
            'user_id' => $request->user
        ]);
        return response()->json([
            'msg' => $bookmark
        ]);
    }

    //can be used in both panel admin and store
    public function show($id)
    {
        return Bookmark::with(['product'])->where('user_id', $id)->orderByDesc('id')->get();
    }

    public function remove(Request $request)
    {
        return Bookmark::where([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
        ])->delete();
    }

    //can be used in panel admin
    public function showByProduct($product)
    {
        return Bookmark::with('user')->where('product_id', $product)->orderByDesc('id')->get();
    }
}
