<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        Validator::validate($request->all(),
            [
                'email' => "required|email",
                'password' => "required|min:6|max:15",
            ],
            [
                'email.required' => 'لطفا ایمیل را وارد کنید!',
                'email.email' => 'لطفا ایمیل را به درستی وارد کنید!',
                'password.required' => 'لطفا رمز عبور را وارد کنید!',
                'password.min' => 'رمز عبور باید حداقل 6 کاراکتر باشد!',
                'password.max' => 'رمز عبور باید حداکثر 15 کاراکتر باشد!'
            ]);


        $admin = Admin::where([
            'email' => $request->email
        ])->first();
        //create user token
        if (!$admin || !Hash::check($request->password ,$admin->password)){
            return response()->json([
                'msg' => 'اطلاعات وارد شده صحیح نمیباشد!'
            ],401);

        }

        $token = $admin->createToken('account')->plainTextToken;

        return response()->json([
            'user' => $admin,
            'token' => $token
        ], 201);
    }

    public function register(Request $request)
    {
        Validator::validate($request->all(),
            [
                'email' => "bail|required|email|unique:admins",
                'password' => "required|min:6|max:15",
            ],
            [
                'email.required' => 'لطفا ایمیل را وارد کنید!',
                'email.email' => 'لطفا ایمیل را به درستی وارد کنید!',
                'password.required' => 'لطفا رمز عبور را وارد کنید!',
                'password.min' => 'رمز عبور باید حداقل 6 کاراکتر باشد!',
                'password.max' => 'رمز عبور باید حداکثر 15 کاراکتر باشد!',
                'email.unique' => 'کاربری با این اطلاعات وجود ندارد!',
            ]);

        $password = bcrypt($request->password);
        $admin = Admin::create([
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role
        ]);
        if (!$admin)   return response()->json([
            'msg' => 'خطا در ایجاد کاربر'
        ], 402);

        return response()->json([
            'user' => $admin
        ], 201);
    }
}
