<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    private $customerObj;

    public function __construct()
    {
        $this->customerObj = new Customer();
    }
    public function indexCustomer(Request $request)
    {
        $new_customer = $this->customerObj->query()->create([
            'user_name' => $request->user_name,
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'response' => 'مشتری افزوده شد.',
            'return' => $new_customer
        ]);
    }

    public function showCustomers()
    {
        return $this->customerObj->query()->orderByDesc('id')->get();
    }

    public function editCustomer($id ,Request $request)
    {
        $edit_customer = $this->customerObj->query()->find($id)->update([
            'user_name' => $request->user_name,
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'response' => 'اطلاعات مشتری آپدیت شد.',
            'return' => $edit_customer
        ]);
    }

    public function deleteCustomer($id)
    {
        return $this->customerObj->find($id)->delete();
    }

    public function checkAccess(Request $request)
    {
        Validator::validate(
            $request->all(),
            [
                'user_name' => "required|string",
                'password' => "required|string",
            ],
            [
                'user_name.required' => 'لطفا نام کاربری خود را وارد کنید!',
                'user_name.string' => 'لطفا نام کاربری خود را به درستی وارد کنید!',
                'password.required' => 'لطفا گذرواژه خود را وارد کنید!',
                'password.string' => 'لطفا گذرواژه خود را به درستی وارد کنید!'
            ]
        );
        $customer_exits = $this->customerObj->where('user_name',$request->user_name)->first();
        if (!$customer_exits || !Hash::check($request->password,$customer_exits->password)){
            return response([
                'message' => 'شما اجازه دسترسی به ویدئو های آموزشی را ندارید.'
            ],401);
        }
        return $this->addUserToCustomers($request->id);
    }

    public function addUserToCustomers($id)
    {
        $user = User::query()->where('id',$id)->update(array('is_customer'=>1));
        return response()->json([
            'user' => $user
        ],200);
    }
}
