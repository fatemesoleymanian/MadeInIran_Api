<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\Permission;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mailer\Exception\ExceptionInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    ////////////////********* this methods have been tested => OK!
    public function login(Request $request)
    {
        Validator::validate(
            $request->all(),
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
            ]
        );


        $admin = Admin::with('role')->where([
            'email' => $request->email
        ])->first();
        //create user token
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'اطلاعات وارد شده صحیح نمیباشد!'
            ], 401);
        }

        $permission = Permission::with('module')->where('role_id', $admin->role_id)->get();
        $token = $admin->createToken('panel')->plainTextToken;
        

        return response()->json([
            'user' => $admin,
            'token' => $token,
            'permission' => $permission,
        ], 201);
    }

    //super admin register others
    public function register(Request $request)
    {
        Validator::validate(
            $request->all(),
            [
                'email' => "bail|required|email|unique:admins",
                'password' => "required|min:6|max:15",
                'role' => "required",
                'phone_number' => "required",
                'address' => "required",
                'username' => "required",
            ],
            [
                'email.required' => 'لطفا ایمیل را وارد کنید!',
                'role.required' => 'لطفا نقش را وارد کنید!',
                'email.email' => 'لطفا ایمیل را به درستی وارد کنید!',
                'password.required' => 'لطفا رمز عبور را وارد کنید!',
                'password.min' => 'رمز عبور باید حداقل 6 کاراکتر باشد!',
                'password.max' => 'رمز عبور باید حداکثر 15 کاراکتر باشد!',
                'email.unique' => 'کاربری با این ایمیل وجود دارد!',
            ]
        );

        $password = bcrypt($request->password);
        $admin = Admin::create([
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'username' => $request->username,
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
        // $admins = Cache::remember('admins', now()->addHour(1), function () {
        $admins = Admin::with('role')->orderByDesc('id')->get();
        // });
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
        Validator::validate(
            $request->all(),
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
            ]
        );

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

        Validator::validate(
            $request->all(),
            ['email' => "required|email"],
            [
                'email.required' => 'لطفا ایمیل خود را وارد کنید!',
                'email.email' => 'لطفا ایمیل خود را به درستی وارد کنید!'
            ]
        );

        $email = $request->email;
        $conf_code = rand(10000, 100000);
        $user = Admin::where('email', $email)->first();

        if (!$user) return response()->json([
            'message' => 'کاربری با این ایمیل در پنل وجود ندارد!'
        ], 401);

        try {
            Mail::send(
                'mail.conf_code',
                ['code' => $conf_code],
                function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('کد تایید ساخت ایران');
                }
            );
            cache()->remember($email, 250, function () use ($conf_code) {
                return $conf_code;
            });
        } catch (ExceptionInterface $e) {
            return response()->json([
                // 'message' => 'خطا در ارسال ایمیل به کاربر'
                'message' => $e
            ], 401);
        }
        return response()->json([
            'message' => 'کد تایید به شما ایمیل شد.',
            'code' => $conf_code
        ]);
    }

    public function resetPassword(Request $request)
    {
        Validator::validate(
            $request->all(),
            ['code' => "required"],
            ['code.required' => 'لطفا کد تایید را وارد کنید!']
        );

        $email = $request->email;
        $code = cache()->get($email);
        if ($code != $request->code) return response()->json([
            'message' => 'کد تایید وارد شده صحیح نیست!'
        ], 401);
        $admin = Admin::with('role')->where('email', $email)->first();
        $token = $admin->createToken('panel')->plainTextToken;
        $permission = Permission::with('module')->where('role_id', $admin->role_id)->get();

        return response()->json([
            'admin' => $admin,
            'token' => $token,
            'permission' => $permission

        ], 201); //redirect user to update password in front

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(
            [
                'message' => '.از حساب کاربری خود خارج شدید'
            ]
        );
    }
    ///********** search in tags , blogs and products in admin panel*****//
    public function adminSearch($str)
    {
        if ($str) {

            $product = Product::with(['category', 'state', 'tag'])
                ->when($str != '', function (Builder $q) use ($str) {
                    $q->where('name', 'LIKE', "%{$str}%")
                        ->orWhereHas('category', function (Builder $builder) use ($str) {
                            $builder->where('name', 'LIKE', "%{$str}%");
                        })
                        ->orWhereHas('tag', function (Builder $builder) use ($str) {
                            $builder->where('name', 'LIKE', "%{$str}%");
                        })
                        ->orWhereHas('state', function (Builder $builder) use ($str) {
                            $builder->where('type', 'LIKE', "%{$str}%");
                        });
                })->get();
        }

        $blog = Blog::with(['tag', 'category'])
            ->when($str != '', function (Builder $q) use ($str) {
                $q->where('title', 'LIKE', "%{$str}%")
                    ->orWhereHas('category', function (Builder $builder) use ($str) {
                        $builder->where('name', 'LIKE', "%{$str}%");
                    })
                    ->orWhereHas('tag', function (Builder $builder) use ($str) {
                        $builder->where('name', 'LIKE', "%{$str}%");
                    });
            })->get();

        $tag = DB::table('tags')->where('name', 'LIKE', "%{$str}%")->get();
        //age paginate nmikhay ->get() bzar tash na paginate()
        return response()->json([
            'products' => $product,
            'blogs' => $blog,
            'tags' => $tag
        ]);
    }
}
