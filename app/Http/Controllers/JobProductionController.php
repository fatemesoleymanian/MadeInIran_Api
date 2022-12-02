<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\JobProduction;
use App\Notifications\UserActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;

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
            'product' => $request->product,
        ]);

        $data = ['action' => 'فرم تقاضای خط تولید بدون ایده'];
        //create notification
        $admin = Admin::query()->first();
        Notification::send($admin, new UserActions($data));
        //end

        //create response
        $response = [
            'user' => $user,
            'msg' => Lang::get('messages.under_const_appreciation')
        ];

        return response()->json($response, 201);
    }

    public function show()
    {
        return JobProduction::orderByDesc('id')->get();
    }
}
