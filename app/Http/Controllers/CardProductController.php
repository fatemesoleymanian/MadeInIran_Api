<?php

namespace App\Http\Controllers;

use App\Models\CardProduct;
use Illuminate\Http\Request;

class CardProductController extends Controller
{
    ////////////////********* this methods have been tested => OK!
    public function showCards($product)
    {

        $count = CardProduct::where('product_id', $product)->get()->count();
        return response()->json([
            'count' => $count
        ]);
    }

    public function increaseQuantity(Request $request)
    {
        return CardProduct::where('id', $request->id)->increment('count');
    }
    public function decreaseQuantity(Request $request)
    {
        return CardProduct::where('id', $request->id)->decrement('count');
    }
}
