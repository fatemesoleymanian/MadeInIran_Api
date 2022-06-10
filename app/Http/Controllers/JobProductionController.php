<?php

namespace App\Http\Controllers;

use App\Models\JobProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class JobProductionController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'phone_number' => 'required|numeric|digits:11',
        ]);
        $user = JobProduction::create([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
        ]);
        //create response
        $response = [
            'user' => $user,
            'msg' => Lang::get('messages.under_const_appreciation')
        ];

        return response()->json($response, 201);
    }
    public function show()
    {
        $users = JobProduction::orderByDesc('id')->paginate(10);
        return response()->json([
            'users' => $users
        ]);
    }
}
