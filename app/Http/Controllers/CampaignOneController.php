<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwoFieldFormsRequest;
use App\Models\CampaignOne;
use Illuminate\Http\Request;

class UserCampaignOneController extends Controller
{
    public function store(TwoFieldFormsRequest $request)
    {
        $request->validated();
        $data = [
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
        ];
       $form = CampaignOne::query()->create($data);
        if (!$form)  return response()->json([
            'message' => "خطایی در ثبت اطلاعات رخ داد",
        ],200);

            return response()->json([
            'message' => "اطلاعات با موفقیت ثبت گردید، منتظر تماس ما باشید.",
        ],201);
    }

    public function index()
    {
        return CampaignOne::query()->get();
    }

    public function show($id)
    {
        return CampaignOne::query()->where('id',$id)->first();
    }
}
