<?php

namespace App\Http\Controllers;

use App\Http\Requests\fqRequest;
use App\Models\FQ;
use App\Models\FQProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Throwable;

class FQController extends Controller
{
    //panel
    public function save(fqRequest $request)
    {
        $request->validated();
        $products = [];

        DB::beginTransaction();
        try {
            $faq = FQ::create([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);
            // insert FAQ products
            foreach ($request->product as $p) {
                array_push($products, ['product_id' => $p, 'fq_id' => $faq->id]);
            }
            FQProduct::insert($products);
            DB::commit();
            return response()->json([
                'msg' => Lang::get('messages.success', ['attribute' => 'پرس و پاسخ متداول']),
                'faq' => $faq
            ]);
        } catch (Throwable $throwable) {
            DB::rollback();
            return response()->json([
                'errors' => Lang::get('messages.fail', ['attribute' => 'پرس و پاسخ متداول']),
                'throw' => $throwable
            ], 401);
        }
    }
    public function update(fqRequest $request, $id)
    {
        $request->validated();
        $products = [];

        DB::beginTransaction();
        try {
            $faq = FQ::where('id', $id)->update([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);

            FQProduct::where('fq_id', $id)->delete();
            // insert FAQ products
            foreach ($request->product as $p) {
                array_push($products, ['product_id' => $p, 'fq_id' => $id]);
            }
            FQProduct::insert($products);
            DB::commit();
            return response()->json([
                'msg' => ' پرس و پاسخ متداول ویرایش گردید.',
                'faq' => $faq
            ]);
        } catch (Throwable $throwable) {
            DB::rollback();
            return response()->json([
                'errors' => 'خطا در ویرایش.',
                'throw' => $throwable
            ], 401);
        }
    }
    public function showAll()
    {
        return FQ::with(['product'])->orderByDesc('id')->paginate(10);
    }
    public function delete(Request $request)
    {
        $faq = FQ::where('id', $request->id)->delete();
        if ($faq) return response()->json([
            'msg' => 'عملیات حذف موفق!'
        ], 200);
        else return response()->json([
            'msg' => 'عملیات حذف ناموفق!'
        ], 401);
    }
}
