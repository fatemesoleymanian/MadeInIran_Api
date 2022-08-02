<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequesForRepresentation;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            'product' => $request->product,
            'reasons' => $request->reasons,
            'experts' => $request->experts
        ]);

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
}
