<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CardController extends Controller
{
    ////////////////********* this methods have been tested => OK!
    public function save(Request $request)
    {
        $card_id = Card::where([
            'user_id' => $request->user_id,
            'status' => 1
        ])->first();
        $add = CardProduct::create([
            'card_id' => $card_id->id,
            'product_id' => $request->product,
            'state_id' => $request->state
        ]);
        return response()->json([
            'msg' => bcrypt($add)
        ]);
    }

    public function show($user)
    {
        $card_id = Card::where([
            'user_id' => $user,
            'status' => 1
        ])->first();

        $products = CardProduct::with(['product', 'state'])->where('card_id', $card_id->id)->get();
        return response()->json([
            'products' => $products
        ]);
    }

    public function remove(Request $request)
    {
        $card_id = Card::where([
            'user_id' => $request->user_id,
            'status' => 1
        ])->first();
        $card = CardProduct::where([
            'card_id' => $card_id->id,
            'product_id' => $request->product,
            'state_id' => $request->state
        ])->delete();
        return response()->json([
            'msg' => $card
        ]);
    }

    public function countOfProduct($user)
    {
        $card_id = Card::where([
            'user_id' => $user,
            'status' => 1
        ])->first();

        $products = CardProduct::where('card_id', $card_id->id)->get()->count();
        return response()->json([
            'count' => $products
        ]);
    }

    //panel admin
    //    public function showByUser($id)
    //    {
    //
    //    }

}
