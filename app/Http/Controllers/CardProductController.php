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

    public function changeCount(Request $request)
    {
       return CardProduct::where('id' , $request->id)->update([
           'count' => $request->count
       ]);
    }
}
