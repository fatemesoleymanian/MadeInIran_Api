<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequesForRepresentation;
use App\Models\Admin;
use App\Models\DelseyForm;
use App\Notifications\UserActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mailer\Exception\ExceptionInterface;

class DelseyFormController extends Controller
{
    public function save(RequesForRepresentation $request)
    {
        $request->validated();
        $res = DelseyForm::create([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'age' => $request->age,
            'education' => $request->education,
            'course' => $request->course,
            'work_experience' => $request->work_experience,
            'job' => $request->job,
            'selected_package' => $request->selected_package,
            'reasons' => $request->reasons,
            'experts' => $request->experts
        ]);

        //create notification
        $data = ['action'=>'فرم دلسی '];
        $admin = Admin::query()->first();
        Notification::send($admin,new UserActions($data));
        //end

        try {
            Mail::send('mail.catalog_form', [
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'age' => $request->age,
                'education' => $request->education,
                'course' => $request->course,
                'work_experience' => $request->work_experience,
                'job' => $request->job,
                'selected_package' => $request->selected_package,
                'reasons' => $request->reasons,
                'experts' => $request->experts,
                'created_at' => $res->created_at
            ], function ($message) {
                $message->to('pedichbiz@gmail.com');
//                $message->to('soleymanian.usc@gmail.com');
                $message->subject('فرم درخواست نمایندگی دستمال کاغذی دلسی');
            });
        }
        catch (ExceptionInterface $e) {
            return response()->json([
                'msg' => '0'
            ]);
        }
        if ($res) {
            return response()->json([
                'msg' => 'اطلاعات با موفقیت ثبت شد!'
            ]);
        }
        else {
            return response()->json([
                'msg' => $res
            ]);
        }

    }
}
