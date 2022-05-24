<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequesForRepresentation;
use App\Models\Request_for_representation;
use Illuminate\Http\Request;

class RequestForRepresentationController extends Controller
{
    public function save(RequesForRepresentation $request)
    {
        $request->validated();
        $res = Request_for_representation::insert($request->all());
        if ($res) {
            return response()->json([
                'msg' => 'اطلاعات با موفقیت ثبت شد!'
            ]);
        }
        else
        {
            return response()->json([
                'msg' => $res
            ]);
        }
    }
}
