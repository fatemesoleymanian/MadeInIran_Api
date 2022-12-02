<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequesForRepresentation;
use App\Models\Admin;
use App\Models\Request_for_representation;
use App\Notifications\UserActions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mailer\Exception\ExceptionInterface;
use Illuminate\Http\Request;

class RequestForRepresentationController extends Controller
{
    public function save(RequesForRepresentation $request)
    {
        $request->validated();
        $res = Request_for_representation::create([
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

        $data = [ 'action' => 'فرم درخواست نمایندگی دستمال کاغذی رومیزی'];
        //create notification
        $admin = Admin::query()->first();
        Notification::send($admin, new UserActions($data));
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
                $message->subject('فرم درخواست نمایندگی دستمال کاغذی رومیزی');
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
