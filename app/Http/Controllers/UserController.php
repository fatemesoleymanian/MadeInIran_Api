<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Jobs\EmailJob;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\User;
use App\Notifications\UserActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mailer\Exception\ExceptionInterface;
use Kavenegar\KavenegarApi;

class UserController extends Controller
{
    ////////////////********* this methods have been tested => OK!
    public function createAccountForCustomer()
    {
//        return User::query()->find(399);
//                $user=User::query()->find(637);
//        $customer = Customer::query()->find(160);
//////
//        return User::query()->where('id', 637)->update([
//            'name' => $customer->name,
//            'phone_number' => $customer->user_name,
//            'password' => $customer->password,
//        'is_customer' => 1
//        ]);
        //
//        $clients = Customer::query()->get();
//        foreach ($clients as $client)
//        {
//            DB::beginTransaction();
//            try {
//            User::query()->where('phone_number',$client->user_name)
//                ->update([
//                   'is_customer' => 1
//                ]);
//                DB::commit();
//            }
//            catch (\Exception $exception){
//                DB::rollBack();
//                throw new \Exception($exception->getMessage());
//            }
//        }
//                $clients = Customer::query()->where('id','>','159')->get();
//                foreach ($clients as $client){
//                    DB::beginTransaction();
//                    try {
//                        $user = User::query()->updateOrCreate([
//                            'name' => $client->name,
//                            'phone_number' => $client->user_name,
//                            'password' => $client->password,
//                            'is_customer' => 1
//                        ]);
//        //                $card = Card::create([
//        //                    'user_id' => $user->id,
//        //                    'status' => 1
//        //                ]);
//                        DB::commit();
//                    }
//                    catch (\Exception $exception){
//                        DB::rollBack();
//                        throw new \Exception($exception->getMessage());
//                    }
//                }
//                return Customer::query()->where('user_name','09039137214')->first();
                return User::query()->where('phone_number','09137686428')->first();
    }

    public function login(LoginRequest $request)
    {
        $request->validated();
        $user = User::where('phone_number', $request->username)->orWhere('email', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'اطلاعات وارد شده صحیح نمیباشد!'
            ]);
        }

        $token = $user->createToken('account')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function forgetPassword(Request $request)
    {
        Validator::validate(
            $request->all(),
            ['username' => "required"],
            [
                'email.required' => 'لطفا نام کاربری خود را وارد کنید!',
            ]
        );

        $username = $request->username;
        $otp = rand(10000, 1000000);
        $user = User::where('phone_number', $request->username)->orWhere('email', $request->username)->first();

        if (!$user) return response()->json([
            'message' => 'نام کاربری درسیستم وجود ندارد!'
        ],200);

        // phone number
        if (str_starts_with($username, '09')) {

            $kavenegar = new KavenegarApi(config('kavenegar.apikey'));
            $kavenegar->VerifyLookup(
                $username,
                $otp,
                null,
                null,
                'verify',
                $type = null
            );

            cache()->remember($username, 250, function () use ($otp) {
                return $otp;
            });
            return response()->json(['message' => 'رمز یکبار مصرف به شما پیامک شد.'],201);
        }
        //EMAIL
        else {

            try {
                //email with job and queue
                //                $details['view'] = 'mail.conf_code';
                //                $details['conf_code'] = $conf_code;
                //                $details['key'] = $request->key;
                //                dispatch(new EmailJob($details));

                //no queue
                Mail::send(
                    'mail.forget_password',
                    ['code' => $otp],
                    function ($message) use ($request) {
                        $message->to($request->username);
                        $message->subject('ساخت ایران');
                    }
                );
                cache()->remember($username, 250, function () use ($otp) {
                    return $otp;
                });
                return response()->json(['message' => 'رمز یکبار مصرف به شما ایمیل شد.'],201);
            } catch (ExceptionInterface $e) {
                return response()->json([
                    'email' => $e
                ], 200);
            }
        }
    }

    public function resetPassword(Request $request)
    {
        Validator::validate(
            $request->all(),
            ['otp' => "required"],
            ['otp.required' => 'لطفا رمز یکبار مصرف را وارد کنید!']
        );

        $username = $request->username;
        $otp = cache()->get($username);
        if ($otp != $request->otp) return response()->json([
            'message' => 'رمز وارد شده صحیح نیست!'
        ], 200);

        $user = User::where('phone_number', $request->username)->orWhere('email', $request->username)->first();
        $token = $user->createToken('account')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);

    }

    public function register(Request $request)
    {
        $user = User::where('phone_number', $request->key)->orWhere('email', $request->key)->first();
        if ($user)  return response()->json([
            'message' => 'این کاربر در سیستم وجود دارد!'
        ], 200);

        $key = $request->key;
        $conf_code = rand(10000, 100000);

        // phone number
        if (str_starts_with($key, '09')) {
            Validator::validate(
                $request->all(),
                ['key' => "required"],
                ['key.required' => 'لطفا شماره تلفن همراه یا ایمیل خود را وارد کنید!']
            );

            $kavenegar = new KavenegarApi(config('kavenegar.apikey'));
            $kavenegar->VerifyLookup(
                $key,
                $conf_code,
                null,
                null,
                'verify',
                $type = null
            );

            cache()->remember($key, 250, function () use ($conf_code) {
                return $conf_code;
            });
            return response()->json(['message' => 'کد تایید به شما پیامک شد.'],201);
        }
        //EMAIL
        else {
            Validator::validate(
                $request->all(),
                ['key' => "required|email"],
                [
                    'key.required' => 'لطفا شماره تلفن همراه یا ایمیل خود را وارد کنید!',
                    'key.email' => 'لطفا شماره تلفن همراه یا ایمیل خود را به درستی وارد کنید!'
                ]
            );

            try {
                //email with job and queue
                //                $details['view'] = 'mail.conf_code';
                //                $details['conf_code'] = $conf_code;
                //                $details['key'] = $request->key;
                //                dispatch(new EmailJob($details));

                //no queue
                Mail::send(
                    'mail.conf_code',
                    ['code' => $conf_code],
                    function ($message) use ($request) {
                        $message->to($request->key);
                        $message->subject('کد تایید ساخت ایران');
                    }
                );
                cache()->remember($key, 250, function () use ($conf_code) {
                    return $conf_code;
                });
                return response()->json(['message' => 'کد تایید به شما ایمیل شد.'],201);

            } catch (ExceptionInterface $e) {
                return response()->json([
                    'email' => $e
                ]);
            }
        }
    }

    public function finishRegister(Request $request)
    {
        Validator::validate(
            $request->all(),
            ['code' => "required"],
            ['code.required' => 'کد تایید را وارد کنید!']
        );
        $key = $request->key;
        $code = cache()->get($key);

        if ($code == $request->code) {
            //register
                if (str_starts_with($key, '09')) {
                    $user = User::create([
                        'phone_number' => $key
                    ]);
                } else {
                    $user = User::create([
                        'email' => $key
                    ]);
                }
                //create notification
                $data = ['action' => 'ایجاد حساب کاربری'];
                $admin = Admin::query()->first();
                Notification::send($admin, new UserActions($data));
                //end

            $token = $user->createToken('account')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 201);
        }
        return response()->json([
            'msg' => 'کد تایید وارد شده صحیح نمی باشد.'
        ]);
    }

    public function update(Request $request)
    {
        Validator::validate(
            $request->all(),
            [
                'phone_number' => 'min:11|max:11|digits:11',
                'address' => 'string',
                'name' => 'string',
            ],
            [
                'phone_number.min' => 'لطفا شماره تلفن همراه را به درستی  وارد کنید!',
                'phone_number.max' => 'لطفا شماره تلفن همراه را به درستی  وارد کنید!',
                'phone_number.digits' => 'لطفا شماره تلفن همراه را به درستی وارد کنید!',

            ]
        );
        $password = str();
        if ($request->password) {
            $password = bcrypt($request->password);
        }

        $user = User::where('id', $request->id)->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'home_number' => $request->home_number,
            'national_id' => $request->national_id,
            'job' => $request->job,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'password' => $password,
            'address' => $request->address,
            'plaque' => $request->plaque,
            'zip_code' => $request->zip_code,
            'floor' => $request->floor,
        ]);
        return response()->json([
            'msg' => $user
        ]);
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

    public function deleteAccount(Request $request)
    {
        return User::where('id', $request->id)->delete();
    }

    public function show($id)
    {
        return User::where('id', $id)->first();
    }

    public function showAll()
    {
        //        return User::orderByDesc('created_at')->paginate(10);
        return User::orderByDesc('created_at')->get();
    }

    public function showCommentsUserMade($id)
    {
        return User::with(['comment', 'blogComment'])->where('id', $id)->get();
    }


    //forget password dige ndrim chon ramza ye bar msrfe
    //az jadvale reset password vase activation code estefade kn  ya cache

}
