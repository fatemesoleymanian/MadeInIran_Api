<?php

namespace App\Http\Controllers;

use App\Models\FQForm;
use Illuminate\Http\Request;

class FQFormController extends Controller
{
    public function save(Request $request)
    {
        $this->validate($request, [
            'faq_id' => 'required',
            'phone_number' => 'required|digits:11',
            'full_name' => 'required'
        ]);
        $form = FQForm::create([
            'fq_id' => $request->faq_id,
            'phone_number' => $request->phone_number,
            'full_name' => $request->full_name
        ]);
        if ($form) return response()->json([
            'msg' => 'اطلاعات فرم با موفقیت ارسال شد! منتظر تماس کارشناسان ما بمانید.',
            'faq_form' => $form
        ], 200);
        else return response()->json([
            'msg' => 'خطایی در ارسال فرم رخ داد.',
        ], 401);
    }
    public function show()
    {
        return FQForm::with(['faq'])->orderByDesc('id')->get();
    }
}
