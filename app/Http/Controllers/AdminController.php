<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mailer\Exception\ExceptionInterface;

class AdminController extends Controller
{
    ////////////////********* this methods have been tested => OK!
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
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'msg' => 'اطلاعات وارد شده صحیح نمیباشد!'
            ], 401);

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
                'role' => "required",
            ],
            [
                'email.required' => 'لطفا ایمیل را وارد کنید!',
                'role.required' => 'لطفا نقش را وارد کنید!',
                'email.email' => 'لطفا ایمیل را به درستی وارد کنید!',
                'password.required' => 'لطفا رمز عبور را وارد کنید!',
                'password.min' => 'رمز عبور باید حداقل 6 کاراکتر باشد!',
                'password.max' => 'رمز عبور باید حداکثر 15 کاراکتر باشد!',
                'email.unique' => 'کاربری با این ایمیل وجود دارد!',
            ]);

        $password = bcrypt($request->password);
        $admin = Admin::create([
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role
        ]);
        if (!$admin) return response()->json([
            'msg' => 'خطا در ایجاد کاربر'
        ], 402);

        return response()->json([
            'user' => $admin
        ], 201);
    }

    public function showAll()
    {
        $admins = Cache::remember('admins',now()->addHour(1),function (){
            return Admin::all();
        });
        return response()->json(
            [
                'admins' => $admins
            ]
        );
    }

    public function showOne($id)
    {
        $admin = Admin::where('id', $id)->first();
        return response()->json(
            [
                'admin' => $admin
            ]
        );
    }

    public function update(Request $request)
    {
        Validator::validate($request->all(),
            [
                'email' => "bail|required|email",
                'password' => "required|min:6|max:15",
                'role_id' => "required|integer",
                'address' => "string",
                'phone_number' => "digits:11",
                'username' => "string",
            ],
            [
                'email.required' => 'لطفا ایمیل را وارد کنید!',
                'email.email' => 'لطفا ایمیل را به درستی وارد کنید!',
                'password.required' => 'لطفا رمز عبور را وارد کنید!',
                'password.min' => 'رمز عبور باید حداقل 6 کاراکتر باشد!',
                'password.max' => 'رمز عبور باید حداکثر 15 کاراکتر باشد!',
                'role_id.required' => 'لطفا نقش کاربر را وارد کنید!',
                'role_id.integer' => 'لطفا نقش کاربر را به درستی وارد کنید!',
                'address.string' => 'لطفا آدرس را به درستی وارد کنید!',
                'phone_number.digits' => 'لطفا شماره تلفن همراه را به درستی وارد کنید!',
                'username.string' => 'لطفا نام کاربری را به درستی وارد کنید!',
            ]);

        $password = bcrypt($request->password);
        return Admin::where('id', $request->id)->update([
            'username' => $request->username,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'address' => $request->address,
            'role_id' => $request->role_id,
            'password' => $password,
        ]);
    }

    public function delete(Request $request)
    {
        return Admin::where('id', $request->id)->delete();

    }

    public function forgetPassword(Request $request)
    {

        Validator::validate($request->all(),
            ['email' => "required|email"],
            ['email.required' => 'لطفا ایمیل خود را وارد کنید!',
                'email.email' => 'لطفا ایمیل خود را به درستی وارد کنید!']);

        $email = $request->email;
        $conf_code = rand(10000, 100000);
        $user = Admin::where('email', $email)->first();

        if (!$user) return response()->json([
            'msg'=>'کاربری با این ایمیل در پنل وجود ندارد!'
        ],401);

        try {
            Mail::send('mail.conf_code', ['code' => $conf_code],
                function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('کد تایید ساخت ایران');
                });
            cache()->remember($email, 250, function () use ($conf_code) {
                return $conf_code;
            });

        } catch (ExceptionInterface $e) {
            return response()->json([
                'email' => '0'
            ]);
        }
        return response()->json([
            'msg' => 'کد تایید به شما ایمیل شد.'
        ]);
    }

    public function resetPassword(Request $request)
    {
        Validator::validate($request->all(),
            ['code' => "required"],
            ['code.required' => 'لطفا کد تایید را وارد کنید!']);

        $email = $request->email;
        $code = cache()->get($email);
        if ($code != $request->code) return response()->json([
            'msg' => 'کد تایید وارد شده صحیح نیست!'
        ]);
        $admin = Admin::where('email', $email)->first();
        $token = $admin->createToken('account')->plainTextToken;

        return response()->json([
            'admin' => $admin,
            'token' => $token
        ], 201);//redirect user to update password in front

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(
            [
                'msg' => '.از حساب کاربری خود خارج شدید'
            ]
        );
    }
}
