<?php

namespace App\Http\Controllers;

use App\Jobs\EmailJob;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mailer\Exception\ExceptionInterface;
use Kavenegar\KavenegarApi;

class UserController extends Controller
{
    ////////////////********* this methods have been tested => OK!
    public function createAccountForCustomer()
    {
        $clients = Customer::query()->get();
        foreach ($clients as $client){
            DB::beginTransaction();
            try {
                $user = User::query()->create([
                    'name' => $client->name,
                    'phone_number' => $client->user_name,
                    'password' => $client->password,
                ]);
//                $card = Card::create([
//                    'user_id' => $user->id,
//                    'status' => 1
//                ]);
                DB::commit();
            }
            catch (\Exception $exception){
                DB::rollBack();
                throw new \Exception($exception->getMessage());
            }
        }
    }

    public function loginOrRegister(Request $request)
    {
        $key = $request->key;
        $conf_code = rand(10000, 100000);
        $status = 0;

        // phone number
        if (str_starts_with($key, '09')) {
            Validator::validate(
                $request->all(),
                ['key' => "required"],
                ['key.required' => 'لطفا شماره تلفن همراه یا ایمیل خود را وارد کنید!']
            );
            $user = User::where('phone_number', $key)->first();
            //login flag
            if ($user) $status = 1;

            $kavenegar = new KavenegarApi(config('kavenegar.apikey'));
             $kavenegar->VerifyLookup(
                $key,
                $conf_code,
                null, null, 'verify', $type = null
            );

            cache()->remember($key, 250, function () use ($conf_code) {
                return $conf_code;
            });
            return $status;
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

            $user = User::where('email', $key)->first();

            //login flag
            if ($user) $status = 1;

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
            } catch (ExceptionInterface $e) {
                return response()->json([
                    'email' => $e
                ], 200);
            }
            return $status;
        }
    }


    public function finishLogin(Request $request)
    {
        Validator::validate(
            $request->all(),
            ['code' => "required"],
            ['code.required' => 'کد تایید را وارد کنید!']
        );
        $key = $request->key;
        $code = cache()->get($key);

        if ($code == $request->code) {
            $user = User::where('phone_number', $key)->orWhere('email', $key)->first();

            //register
            if (!$user) {
                if (str_starts_with($key, '09')) {
                    $user = User::create([
                        'phone_number' => $key
                    ]);
                } else {
                    $user = User::create([
                        'email' => $key
                    ]);
                }
            }

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
        return User::with(['comment','blogComment'])->where('id',$id)->get();

    }


    //forget password dige ndrim chon ramza ye bar msrfe
    //az jadvale reset password vase activation code estefade kn  ya cache

}
