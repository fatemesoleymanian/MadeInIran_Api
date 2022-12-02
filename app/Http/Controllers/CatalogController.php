<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequesForRepresentation;
use App\Models\Admin;
use App\Models\Catalog;
use App\Notifications\UserActions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mailer\Exception\ExceptionInterface;

class CatalogController extends Controller
{
    //panel
    public function showRequests()
    {
        return Catalog::orderByDesc('id')->get();
    }

    //store
    public function save(RequesForRepresentation $request)
    {
        $request->validated();
        $res = Catalog::create([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'age' => $request->age,
            'education' => $request->education,
            'course' => $request->course,
            'work_experience' => $request->work_experience,
            'job' => $request->job,
            'selected_package' => $request->selected_package,
            'selected_model' => $request->selected_model,
            'product' => $request->product,
            'reasons' => $request->reasons,
            'experts' => $request->experts
        ]);


        //create notification
        $data = ['action'=>'فرم درخواست نمایندگی'];
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
                'selected_model' => $request->selected_model,
                'reasons' => $request->reasons,
                'experts' => $request->experts,
                'created_at' => $res->created_at
            ], function ($message) use ($request){
                $message->to('pedichbiz@gmail.com');
//                $message->to('soleymanian.usc@gmail.com');
                $message->subject(' '.'  فرم درخواست نمایندگی'.' '. $request->product);
            });
        }
        catch (ExceptionInterface $e) {
            return response()->json([
                'msg' => $e
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

    public function filter(Request $request)
    {

        return Catalog::query()->when($request->model != '' && $request->state != '', function (Builder $q) use ($request){
            $q->where([
            'selected_package' => $request->state
        ])->orWhere('selected_model',$request->model);
        })
            ->when($request->model != '', function (Builder $q) use ($request){
                $q->where('selected_model',$request->model);
            })
            ->when($request->state != '', function (Builder $q) use ($request){
                $q->where('selected_package' , $request->state);
            })
            ->get();
    }
}
