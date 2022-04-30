<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardProduct;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
//rize products and factor - total
    public function saveCard(Request $request)
    {
        $card_id = Card::where([
            'user_id' => $request->user,
            'status' => 1
        ])->first();

        $products = CardProduct::with(['product', 'state'])->where('card_id', $card_id->id)->get();

        //products
        if (!$products) return response()->json([
            'msg' => 'سبد خرید خالی است!'
        ]);

        $total = 0;
        foreach ($products as $product) {
            if ($product->product->discount == 0.00)
                $total += $product->count * $product->state->price;
            else
                $total += $product->count * $product->state->discounted_price;
        }
        Order::updateOrCreate([
            'card_id' => $card_id->id,
            'status' => 1
        ], ['total' => $total]);

        return response()->json([
            'products' => $products,
            'total_price' => $total
        ]);
    }

    //etela'ate kamele user vase sefaresh
    public function userInfo(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone_number' => 'required|digits:11',
            'address' => 'required',
            'plaque' => 'required|integer',
            'zip_code' => 'required|digits:10',
            'floor' => 'required|integer',
        ]);

        $user = User::where('id', $request->id)->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'plaque' => $request->plaque,
            'zip_code' => $request->zip_code,
            'floor' => $request->floor,
        ]);
        return response()->json([
            'msg' => $user
        ]);
    }

    public function showUserInfo($user)
    {
        return User::where('id', $user)->first();
    }


    //kholaseye sfaresho mibine
    public function showOrder($user)
    {
        $card_id = Card::where([
            'user_id' => $user,
            'status' => 1
        ])->first();

        $products = CardProduct::with(['product', 'state'])->where('card_id', $card_id->id)->get();

        //products
        if (!$products) return response()->json([
            'msg' => 'سبد خرید خالی است!'
        ]);

        $total = 0;
        foreach ($products as $product) {
            if ($product->product->discount == 0.00)
                $total += $product->count * $product->state->price;
            else
                $total += $product->count * $product->state->discounted_price;
        }

        return response()->json([
            'products' => $products,
            'total_price' => $total
        ]);
    }

    //panel and store//show all past orders for user
    public function showAllByUser($user)
    {
        $card_id = Card::where([
            'user_id' => $user,
            'status' => 0
        ])->get('id');
        foreach ($card_id as $c) {
            return Order::where([
                'card_id' => $c->id])->get();
        }
    }

    //panel//all orders
    public function showAll()
    {
        return Order::all();
    }

    //show order state after pardakht
    public function showState($id)
    {
        $current_state = Order::where('id', $id)->first('current_state');
        return response()->json([
            'current_state' => $current_state
        ]);

    }

    public function changeState(Request $request)
    {
        $current_state = Order::where('id', $request->id)->update([
            'current_state' => $request->current_state
        ]);
        return response()->json([
            'current_state' => $current_state
        ]);
    }
}
