<?php

namespace App\Http\Controllers;

use App\Models\under_construction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class UnderConstructionController extends Controller
{
    ////////////////********* this controller hase been tested => OK!
    public function save(Request $request){
        $request->validate([
            'full_name' => 'required|string',
            'phone_number' => 'required|numeric|digits:11',
            'description' => 'required|string'
        ]);
        $user = under_construction::create([
            'full_name'=> $request->full_name,
            'phone_number'=> $request->phone_number,
            'description'=> $request->description,
        ]);
        //create response
        $response = [
            'user' => $user ,
            'msg' => Lang::get('messages.under_const_appreciation')
        ];
        return response()->json($response , 201);
    }
}
