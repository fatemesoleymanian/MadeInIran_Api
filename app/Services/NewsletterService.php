<?php


namespace App\Services;
use App\Models\Admin;
use App\Models\Newsletter;
use App\Notifications\UserActions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;


class NewsletterService
{
    private $newslettersModelObj;

    public function __construct()
    {
        $this->newslettersModelObj = new Newsletter();
    }
    public function addUserToNewslettersReceivers($userInformation)
    {
        $savedWithSuccess = $this->newslettersModelObj->create([
            'full_name'=> $userInformation['full_name'],
            'phone_number'=> $userInformation['phone_number'],
        ]);


        //create notification
        $data = ['action' => 'فرم خبرنامه'];
        $admin = Admin::query()->first();
        Notification::send($admin, new UserActions($data));
        //end

        if (!$savedWithSuccess)  return response()->json(['msg' => 'خطا در ثبت اطلاعات کاربر.']);
        return response()->json([
            'msg' => 'اطلاعات با موفقیت ثبت شد!'
        ]);
    }
    public function showNewslettersReceivers()
    {
        return $this->newslettersModelObj->orderByDesc('id')->get();
    }



}
