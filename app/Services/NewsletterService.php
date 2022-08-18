<?php


namespace App\Services;
use App\Models\Newsletter;


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
