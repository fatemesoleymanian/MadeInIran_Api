<?php

namespace App\Http\Controllers;

use App\Models\under_construction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\ExceptionInterface;

class UnderConstructionController extends Controller
{
    ////////////////********* this controller has been tested => OK!
    public function save(Request $request){
        $request->validate([
            'full_name' => 'required|string',
            'phone_number' => 'required|numeric|digits:11',
            'description' => 'required|string'
        ]);
        $user = under_construction::create([
            'full_name'=> $request->full_name,
            'phone_number'=> $request->phone_number,
            'description'=> $request->description,
        ]);
        //create response
        $response = [
            'user' => $user ,
            'msg' => Lang::get('messages.under_const_appreciation')
        ];
        try {
            Mail::send('mail.under_construction', [
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'description' => $request->description,
            ], function ($message) use ($request) {
                $message->to('sarkhosh.niloufar@gmail.com');
                $message->subject('Under Construction Form');
            });

        }catch (ExceptionInterface $e){
                return response()->json([
                    'msg' => '0'
                ]);
        }

        return response()->json($response , 201);
    }
    public function showAll()
    {
        $users = under_construction::orderByDesc('id')->get();
        try {
            Mail::send('mail.under_construction', [
                'users'=>$users
            ], function ($message) {
                $message->to('sarkhosh.niloufar@gmail.com');
                $message->subject('Under Construction Forms till here');
            });

        }catch (ExceptionInterface $e){
            return response()->json([
                'msg' => '0'
            ]);
        }
    }
}
